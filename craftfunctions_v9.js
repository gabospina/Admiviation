function returnBackground(i) {
	var bg = ["dark-bg", "tint-bg", "", "blue-bg"];
	return (bg[(i % 4)]);
}
var CRAFTS,
	CONTRACTS,
	usedAr = [],
	PILOTS;
$(window).resize(function () {
	$("#craft_info_section").width($(".craftTypes").width() - 330 + "px");
})

//  ========================= BELOW NEW DEEPSEEK =======================

function capitalize(str) {
	return str.charAt(0).toUpperCase() + str.slice(1);
}

$(document).ready(function () {
	$(window).trigger("resize");

	$("#search_crafts").keyup(function () {
		var val = $(this).val().toUpperCase();
		$(".craft-name").each(function () {
			var craftName = $(this).text().toUpperCase();
			var registration = $(this).parent().next().children(".registration-name").text().toUpperCase();
			var className = $(this).parent().next().next().children(".class-name").text().toUpperCase();
			if (className.indexOf(val) != -1 || craftName.indexOf(val) != -1 || registration.indexOf(val) != -1) {
				$(this).parent().parent().show();
			} else {
				$(this).parent().parent().hide();
			}
		});
	});

	$(".sidebar-list a[href='crafts.php'] li").addClass("active");


	loadCrafts(); //Load all the crafts here
	function loadCrafts() {
		$.ajax({
			type: "GET",
			url: "get_all_crafts.php",
			data: { company_id: phpCompanyId },
			success: function (result) {
				console.log("Raw API response:", result); // Log the raw response
				if (result.success) { // Check if the response has a 'success' property
					var res = result.crafts; // Access the 'crafts' array directly
					var crafts = {}; // Changed to object for easier lookup

					// Group crafts by craft name
					for (var i = 0; i < res.length; i++) {
						var craftName = res[i].craft_type; // Updated column name
						if (!crafts[craftName]) {
							crafts[craftName] = [];
						}
						crafts[craftName].push({
							registration: res[i].registration, // Added registration
							tod: res[i].tod,
							craftid: res[i].id,
							alive: res[i].alive,
							company_id: res[i].company_id // Add the company id
						});
					}

					CRAFTS = crafts;

					var craftTable = "<table class='table table-condensed table-bordered craft_table' style='-webkit-box-shadow: 0px 0px 0px 0px rgba(0,0,0,0); -moz-box-shadow: 0px 0px 0px 0px rgba(0,0,0,0); box-shadow: 0px 0px 0px 0px rgba(0,0,0,0);'>" +
						"<thead><th>Craft</th><th>Registration</th><th>Time Of Day</th><th>In Service</th></thead><tbody>";

					// Iterate through the grouped crafts to build the table
					for (var craftName in crafts) {
						if (crafts.hasOwnProperty(craftName)) {
							var craftList = crafts[craftName];
							for (var i = 0; i < craftList.length; i++) {

								// --- CAPITALIZE THE TIME OF DAY ---
								let displayTod = craftList[i].tod;
								if (displayTod) {
									displayTod = capitalize(displayTod);
								} else {
									displayTod = "N/A"; // Or some other default value
								}

								craftTable += "<tr>" +
									"<td><span class='craft-name'>" + craftName + "</span></td>" +
									"<td><span class='registration-name'>" + craftList[i].registration + "</span></td>" + // Added registration
									"<td><span class='craft-tod' data-pk='" + craftList[i].craftid + "'>" + displayTod + "</span></td>" +
									"<td><span class='craft-alive' data-pk='" + craftList[i].craftid + "'>" + (craftList[i].alive ? "True" : "False") + "</span></td>" +
									"<th><button class='btn btn-warning fa fa-minus removeCraftBtn' data-pk='" + craftList[i].craftid + "' data-craft='" + craftName + "'></button></th>" +
									"</tr>";
							}
						}
					}

					craftTable += "<tr>" +
						"<td><input class='form-control' type='text' placeholder='Craft' id='addCraftName'></td>" +
						"<td><input class='form-control' type='text' placeholder='Registration' id='addCraftRegistration'></td>" + // Added registration input
						"<td><select class='form-control' id='addCraftTOD'><option value='day'>Day</option><option value='night'>Night</option></select></td>" +
						"<td><select class='form-control' id='addCraftAlive'><option value='1'>True</option><option value='0'>False</option></select></td>" +
						"<th><button class='btn btn-success fa fa-plus' id='addCraftBtn'></button></th>" +
						"</tr>";

					$("#crafts").html(craftTable);

					// Functions for crafts
					$(".craft-tod").editable({
						type: "select",
						source: { "day": "Day", "night": "Night" },
						url: "update_craft_tod.php",
						name: "craft",
						ajaxOptions: {
							type: "POST",
							cache: false
						},
						success: function (response, newValue) {
							console.log(response);
						}
					});

					$(".craft-alive").editable({
						type: "select",
						source: { "0": "False", "1": "True" },
						url: "update_craft_status.php",
						name: "craft",
						ajaxOptions: {
							type: "POST",
							cache: false
						},
						success: function (response, newValue) {
							console.log(response);
						}
					});
				} else {
					console.error("Error fetching crafts:", result.message);
					alert("Error fetching crafts: " + result.message);
				}
			},
			error: function (xhr, status, error) {
				console.error("AJAX error fetching crafts:", error);
				alert("AJAX error fetching crafts: " + error);
			}
		});
	}
	// 		}
	// 	}
	// });
});

// =============== ABOVE DEPSEEK ======================================


// Attach event handler using delegated event handling to the document
$(document).on('click', '.removeCraftBtn', function () {
	var that = this;
	var craftId = $(this).data("pk");
	var craftName = $(this).data("craft"); // This is actually craft_type

	console.log("Removing craft with ID:", craftId, "and Name:", craftName);

	// --- ADD CONFIRMATION DIALOG ---
	if (confirm("Are you sure you want to remove the craft: " + craftName + "? This action cannot be undone.")) {
		$.ajax({
			type: "POST",
			url: "remove_craft.php",
			dataType: "json", // Expect JSON from the server
			data: { craft: craftId },
			success: function (result) {
				if (result.success) {
					console.log("Craft removed successfully.");
					$(that).closest("tr").remove();
					checkIfLast(craftName);
					alert("The craft '" + craftName + "' was successfully removed."); // SUCCESS ALERT
					loadCrafts() //reloads and ensure that list is updated
				} else {
					console.error("Error removing craft:", result.message);
					let errorMessage = "An unexpected error occurred. Check the console for details.";
					switch (result.message) {
						case "failed_delete_craft":
							errorMessage = "Error: Could not delete the craft is attached to a CONTRACT.";
							break;
						case "failed_prepare_craft":
							errorMessage = "Error: Failed to prepare craft removal statement.";
							break;
						default:
							errorMessage = "Error: " + result.message;  // Show the exact error from PHP
							break;
					}
					alert(errorMessage);
				}
			},
			error: function (xhr, status, error) {
				console.error("AJAX error removing craft:", error);
				alert("AJAX error removing craft. Please check the console for details.");
			}
		});
	} else {
		console.log("Craft removal cancelled by user.");
	}
});

function capitalize(str) {
	return str.charAt(0).toUpperCase() + str.slice(1);
}
// Attach event handler using delegated event handling to the document
$(document).on('click', '#addCraftBtn', function () {
	var that = this;
	var craftName = $("#addCraftName").val(); // This is the craft_type
	var craftClass = $("#addCraftClass").val(); // This is the registration
	var timeOfDay = $("#addCraftTOD").val();
	var alive = $("#addCraftAlive").val();
	var craftRegistration = $("#addCraftRegistration").val(); // Get the registration

	if (craftName && craftRegistration) {
		$.ajax({
			type: "POST",
			url: "add_craft.php",
			dataType: "json",
			data: { craft: craftName, registration: craftRegistration, tod: timeOfDay, alive: alive, company_id: companyId },
			success: function (result) {
				if (result.success) {
					var id = result.craft_id;

					checkIfFirst(craftName);

					var newRow = $("<tr><td><span class='craft-name'>" + craftName + "</span></td><td><span class='class-name'>" + craftRegistration + "</span></td><td><span class='craft-tod' data-pk='" + id + "'>" + capitalize(timeOfDay) + "</span></td><td><span class='craft-alive' data-pk='" + id + "'>" + (alive ? "True" : "False") + "</span></td><th><button class='btn btn-warning fa fa-minus removeCraftBtn' data-pk='" + id + "' data-craft='" + craftName + "'></button></tr>");
					newRow.insertBefore($(that).parent().parent());
					alert("The craft '" + craftName + "' was successfully added."); // ADDED SUCCESS MESSAGE
					loadCrafts(); // Loading again

					// Reinitialize editable fields (Consider optimizing this if performance is an issue)
					$(".craft-tod").editable({
						type: "select",
						source: { "day": "Day", "night": "Night" },
						url: "update_craft_tod.php",
						name: "craft",
						ajaxOptions: { type: "POST", cache: false },
						success: function (response) {
							console.log(response);
						}
					});

					$(".craft-alive").editable({
						type: "select",
						source: { "0": "False", "1": "True" },
						url: "update_craft_status.php",
						name: "craft",
						ajaxOptions: { type: "POST", cache: false },
						success: function (response) {
							console.log(response);
						}
					});
				} else {
					console.error("Error adding craft:", result.message);
					alert("Error adding craft: " + result.message);
				}
			},
			error: function (xhr, status, error) {
				console.error("AJAX error adding craft:", error);
				console.log("Raw response:", xhr.responseText); // Log the raw response
				alert("AJAX error adding craft. Check the console for details.");
			}
		});
	} else {
		if (!craftName) {
			$("#addCraftName").focus();
		} else {
			$("#addCraftRegistration").focus();
		}
	}
});

function getCraftPilots(craftName) {
	table = "<div class='col-md-7 center-block'><table class='table table-condensed table-bordered contract_table' style='-webkit-box-shadow: 0px 0px 0px 0px rgba(0,0,0,0); -moz-box-shadow: 0px 0px 0px 0px rgba(0,0,0,0); box-shadow: 0px 0px 0px 0px rgba(0,0,0,0);'><thead><th>Craft</th><th>Registration</th></thead><tbody>"
	options = "";
	for (var i = 0; i < PILOTS.id.length; i++) {
		if (PILOTS.crafts[i] != null && PILOTS.crafts[i].indexOf(craftName) != -1)
			table += "<tr><td>" + PILOTS.lname[i] + ", " + PILOTS.fname[i] + "</td><th><button class='btn btn-warning fa fa-minus removeContractItem' data-pk='" + PILOTS.id[i] + "' data-index='" + i + "' data-craft='" + craftName + "'></div></button></tr>";
		else
			options += "<option value='" + PILOTS.id[i] + "' data-index='" + i + "'>" + PILOTS.lname[i] + ", " + PILOTS.fname[i] + "</option>";
	}

	table += "<tr id='addPilotsRow'><td colspan='2'><select multiple size='4' class='form-control insertPilot'>" + options + "</select>"

	table += "</td><th><button class='btn btn-success fa fa-plus insertContractBtn' data-pk='" + craftName + "'></button></th></tr></tbody></table></div>";

	$("#craft_info").html(table);

	setUpRemovePilot();
	$(".insertContractBtn").click(function () {
		if ($(".insertPilot").val() != null) {
			var craftName = $(this).data("pk")
			$.ajax({
				type: "POST",
				url: "add_pilots_to_craft.php",
				data: { pilots: JSON.stringify($(".insertPilot").val()), craft: craftName },
				success: function (result) {
					if (result == "success") {
						showNotification("success", "You have added the pilot to the craft");
					} else {
						showNotification("error", "Adding the pilot to the craft was unsuccessful");
					}
					table = "";
					$.each($(".insertPilot").val(), function (ind, val) {
						i = $(".insertPilot option[value='" + val + "']").data("index");
						table += "<tr><td>" + PILOTS.lname[i] + ", " + PILOTS.fname[i] + "</td><th><button class='btn btn-warning fa fa-minus removeContractItem' data-pk='" + PILOTS.id[i] + "' data-index='" + i + "' data-craft='" + craftName + "'></div></button></tr>"
						$(".insertPilot option[value='" + val + "']").remove();
					})
					$(table).insertBefore($("#addPilotsRow"));
					$(".insertPilots").val("");
					setUpRemovePilot();
				}
			})
		}
	})
}

function setUpRemovePilot() {
	$(".removeContractItem").unbind("click").click(function () {
		var row = $(this).parent().parent();
		var id = $(this).data("pk");
		var i = $(this).data("index");
		$.ajax({
			url: "remove_pilot_from_craft.php",
			type: "POST",
			data: { pilot: id, craft: $(this).data("craft") },
			success: function (result) {
				console.log(result);
				if (result == "success") {
					showNotification("success", "You have removed the pilot from the craft.");
					row.remove();
					$("select.insertPilot").append("<option value='" + PILOTS.id[i] + "' data-index='" + i + "'>" + PILOTS.lname[i] + ", " + PILOTS.fname[i] + "</option>");
				} else {
					showNotification("error", "Removing the pilot from the craft was unsuccessful.");
				}
			}
		})
	})
}

function checkIfLast(craft) {
	var last = true;
	$(".craft_table tbody tr").each(function () {
		if ($(this).find(".craft-name").text() == craft)
			last = false;
	})
	if (last) {
		$("#craft_list .select[data-pk='" + craft + "']").remove();
		$("#craft_info").empty();
	}
}

function checkIfFirst(craft) {
	var first = true;
	$(".craft_table tbody tr").each(function () {
		if ($(this).find(".craft-name").text() == craft)
			first = false;
	});
	if (first) {
		$("#craft_list").append("<div class='select' data-pk='" + craft + "'>" + craft + "</div>")
		$(".select").unbind("click").click(function () {
			$(".select").removeClass("selected");
			$(this).addClass("selected");
			getCraftPilots($(this).data("pk"));
		})
	}
}