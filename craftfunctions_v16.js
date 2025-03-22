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

	// Log the companyId to verify it's defined
	console.log("Company ID:", companyId);

	$("#search_crafts").keyup(function () {
		var val = $(this).val().toUpperCase();
		$(".craft-type").each(function () {
			var craftType = $(this).text().toUpperCase();
			var registration = $(this).parent().next().children(".registration-name").text().toUpperCase();
			var className = $(this).parent().next().next().children(".class-name").text().toUpperCase();
			if (className.indexOf(val) != -1 || craftType.indexOf(val) != -1 || registration.indexOf(val) != -1) {
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
			data: { company_id: companyId },
			success: function (result) {
				console.log("Raw API response:", result); // Log the raw response
				if (result.success) { // Check if the response has a 'success' property
					var res = result.crafts; // Access the 'crafts' array directly
					var crafts = {}; // Changed to object for easier lookup

					// Group crafts by craft type
					for (var i = 0; i < res.length; i++) {
						var craftType = res[i].craft_type; // Updated column name
						if (!crafts[craftType]) {
							crafts[craftType] = [];
						}
						crafts[craftType].push({
							registration: res[i].registration, // Added registration
							tod: res[i].tod,
							craftid: res[i].id,
							alive: res[i].alive,
							company_id: res[i].company_id // Add the company id
						});
					}

					CRAFTS = crafts;

					var craftTable = "<table class='table table-condensed table-bordered craft_table' style='-webkit-box-shadow: 0px 0px 0px 0px rgba(0,0,0,0); -moz-box-shadow: 0px 0px 0px 0px rgba(0,0,0,0); box-shadow: 0px 0px 0px 0px rgba(0,0,0,0);'>" +
						"<thead><th>Craft Type</th><th>Registration</th><th>Time Of Day</th><th>In Service</th></thead><tbody>";

					// Iterate through the grouped crafts to build the table
					for (var craftType in crafts) {
						if (crafts.hasOwnProperty(craftType)) {
							var craftList = crafts[craftType];
							for (var i = 0; i < craftList.length; i++) {

								// --- CAPITALIZE THE TIME OF DAY ---
								let displayTod = craftList[i].tod;
								if (displayTod) {
									displayTod = capitalize(displayTod);
								} else {
									displayTod = "N/A"; // Or some other default value
								}

								craftTable += "<tr>" +
									"<td><span class='craft-type'>" + craftType + "</span></td>" +
									"<td><span class='registration-name'>" + craftList[i].registration + "</span></td>" + // Added registration
									"<td><span class='craft-tod' data-pk='" + craftList[i].craftid + "'>" + displayTod + "</span></td>" +
									"<td><span class='craft-alive' data-pk='" + craftList[i].craftid + "'>" + (craftList[i].alive ? "True" : "False") + "</span></td>" +
									"<th><button class='btn btn-warning fa fa-minus removeCraftBtn' data-pk='" + craftList[i].craftid + "' data-craft='" + craftType + "'></button></th>" +
									"</tr>";
							}
						}
					}

					craftTable += "<tr>" +
						"<td><input class='form-control' type='text' placeholder='Craft Type' id='addCraftType'></td>" +
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
});

// Attach event handler using delegated event handling to the document
$(document).on('click', '.removeCraftBtn', function () {
	var that = this;
	var craftId = $(this).data("pk");
	var craftType = $(this).data("craft"); // This is actually craft_type

	console.log("Removing craft with ID:", craftId, "and Type:", craftType);

	// --- ADD CONFIRMATION DIALOG ---
	if (confirm("Are you sure you want to remove the craft: " + craftType + "? This action cannot be undone.")) {
		$.ajax({
			type: "POST",
			url: "remove_craft.php",
			dataType: "json", // Expect JSON from the server
			data: { craft: craftId },
			success: function (result) {
				if (result.success) {
					console.log("Craft removed successfully.");
					$(that).closest("tr").remove();
					checkIfLast(craftType);
					alert("The craft '" + craftType + "' was successfully removed."); // SUCCESS ALERT
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

function checkIfLast(craftType) {
	var last = true;
	$(".craft_table tbody tr").each(function () {
		if ($(this).find(".craft-type").text() == craftType) {
			last = false;
		}
	});
	if (last) {
		// Remove the craft type from the list if it was the last one
		$("#craft_list .select[data-pk='" + craftType + "']").remove();
		$("#craft_info").empty();
	}
}

// Attach event handler using delegated event handling to the document
$(document).on('click', '#addCraftBtn', function () {
	var that = this;
	var craftType = $("#addCraftType").val(); // Craft type
	var craftRegistration = $("#addCraftRegistration").val(); // Registration
	var timeOfDay = $("#addCraftTOD").val(); // Time of day
	var alive = $("#addCraftAlive").val(); // In service status

	// Validate inputs
	if (!craftType || !craftRegistration) {
		alert("Craft type and registration are required.");
		if (!craftType) $("#addCraftType").focus();
		else $("#addCraftRegistration").focus();
		return;
	}

	// Log the data being sent
	console.log("Sending data to add_craft.php:", {
		craft: craftType,
		registration: craftRegistration,
		tod: timeOfDay,
		alive: alive,
		company_id: companyId
	});

	// Send AJAX request
	$.ajax({
		type: "POST",
		url: "add_craft.php",
		dataType: "json",
		data: {
			craft: craftType,
			registration: craftRegistration,
			tod: timeOfDay,
			alive: alive,
			company_id: companyId
		},
		success: function (result) {
			console.log("Response from add_craft.php:", result);
			if (result.success) {
				alert("The craft '" + craftType + "' was successfully added.");
				window.location.reload()
				var id = result.craft_id;

				// Add the new craft to the table
				var newRow = $("<tr>" +
					"<td><span class='craft-type'>" + craftType + "</span></td>" +
					"<td><span class='registration-name'>" + craftRegistration + "</span></td>" +
					"<td><span class='craft-tod' data-pk='" + id + "'>" + capitalize(timeOfDay) + "</span></td>" +
					"<td><span class='craft-alive' data-pk='" + id + "'>" + (alive == 1 ? "True" : "False") + "</span></td>" +
					"<th><button class='btn btn-warning fa fa-minus removeCraftBtn' data-pk='" + id + "' data-craft='" + craftType + "'></button></th>" +
					"</tr>");

				newRow.insertBefore($(that).closest("tr"));
				alert("The craft '" + craftType + "' was successfully added.");
				loadCrafts(); // Reload the crafts list
			} else {
				console.error("Error adding craft:", result.message);
				alert("Error adding craft: " + result.message);
			}
		},
		error: function (xhr, status, error) {
			console.error("AJAX error adding craft:", error);
			console.log("Raw response:", xhr.responseText);
			alert("AJAX error adding craft. Check the console for details.");
		}
	});
});