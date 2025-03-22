function returnBackground(i) {
	var bg = ["dark-bg", "tint-bg", "", "blue-bg"];
	return (bg[(i % 4)]);
}
var CRAFTS,
	CONTRACTS,
	usedAr = [];
//New Alert System:
function showNotification(type, text, timeout) {
	alert(type.toUpperCase() + ": " + text); // Simple alert notification
}

// ======== BELOW JUSTIN =========================

$(document).ready(function () {

	$("#search_contracts").keyup(function () {
		var that = this;
		$(".contract-name").each(function () {
			if ($(this).text().toUpperCase().indexOf($(that).val().toUpperCase()) != -1) {
				$(this).parent().show();
			} else {
				$(this).parent().hide();
			}
		})
	})

	$("#expandAccordion").click(function () {
		if ($(this).hasClass("active")) {
			$(this).removeClass("active");
			$(this).text("Expand All");
			$(".more_info").collapse("hide");
		} else {
			$(this).addClass("active");
			$(this).text("Collapse All");
			$(".more_info").collapse("show");
		}
	});

	$("#printContracts").click(function () {
		$(".more_info").collapse("show");
		window.print();
	})

	$("#newContractColor").spectrum();
	$(".sidebar-list a[href='contracts.php'] li").addClass("active");

	// Load all current data to start all functions
	getContracts();
	// Call function to load current cutomers, from Customer List table
	loadCustomers();

	// Add event listener for showing the "Add New Contract" modal
	$('.addNewContractModal').on('show.bs.modal', function (e) {
		loadCrafts(); // Load crafts into the select box every time the modal is shown
		loadPilots(); // Load pilots into the select box every time the modal is shown
	});
});

// ======== ABOVE JUSTIN =========================

function updateContractColor() {
	$(".contract_head").each(function (i) {
		$(this).removeClass("dark-bg tint-bg blue-bg").addClass(returnBackground(i));
	})
}

function setContractEvents(init) {
	if (!init) {
		$(".colorpicker").spectrum("destroy");
		$("#contracts").sortable("destroy");
		$("#removeContractItem").unbind("click");
	}
	$(".colorpicker").spectrum({
		change: function (color) {
			var id = $(this).parents(".more_info").data("pk");
			var c = color.toHexString();
			$.ajax({
				url: "change_contract_color.php",
				type: "POST",
				data: { color: c, id: id },
				success: function (result) {
					console.log(result);
					showNotification("success", "You successfully changed the color of the contract");
				}
			})
		}
	});

	$(".removeContractItem").click(function () {
		if ($(this).parent().parent().siblings().length == 1) {
			showNotification("error", "Contracts must have at least one craft.")
		} else {
			var that = this;
			$.ajax({
				type: "POST",
				url: "remove_contract_item.php",
				data: { contract: $(this).data("contract"), craft: $(this).data("pk") },
				success: function (result) {
					console.log(result);
					if (result == "success") {
						var className = $(that).parent().siblings(".cls").text();
						var classVal = $(that).data("pk");
						var craftName = $(that).parent().siblings(".crft").text();
						console.log(craftName)
						$(".insertContract, #newContractCraftSelect").each(function () {
							var optgroup = $(this).find("optgroup[label='" + craftName + "']");
							optgroup.append("<option value='" + classVal + "'>" + className + "</option>");
						});
						var copterCount = $(that).parent().parent().siblings().length - 1;
						$(that).parents(".more_info").prev().find(".copterCount").text(copterCount);
						$(that).parents(".more_info").children(".errors").append("<div class=\"col-md-6 center-block\" style=\"margin-left: auto; margin-right: auto;\">" +
							"<div class=\"alert_sec\"><div class=\"alert alert-success alert-dismissible\" role=\"alert\">" +
							"<button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">×<span class=\"sr-only\">Close</span></button>" +
							"<strong>Successfully removed entry.</strong></div></div></div>");
						showNotification("success", "Successfully removed entry.")
						$(that).parent().parent().remove();
					} else {
						showNotification("error", "Failed to update Contract");
					}
				}
			})
		}
	});
}
//  This function displays: Customer, Crafts and Pilots in dd a new Contract Modal

function getContracts() {
	$("#contracts").empty();
	$.ajax({
		type: "GET",
		url: "get_all_contracts.php",
		success: function (result) {
			console.log("Raw API response:", result); // Debugging line

			if (result.success) {
				try {
					const contracts = result.contracts; // Access the contracts array directly

					// Debugging: Log the number of contracts and their IDs
					console.log("Number of contracts:", contracts.length);
					console.log("Contract IDs:", contracts.map(c => c.contractid));

					if (Array.isArray(contracts)) {
						const contractsMap = {}; // Build an object to group contracts by name

						// Group contracts by name and collect crafts
						for (const contract of contracts) {
							const contractKey = contract.contract;

							if (!contractsMap[contractKey]) {
								contractsMap[contractKey] = {
									id: contract.contractid,
									customerid: contract.customerid,
									color: contract.color,
									crafts: []
								};
							}

							if (contract.craft) { // Only add crafts if they exist
								contractsMap[contractKey].crafts.push({
									class: contract.class,
									craft: contract.craft,
									craftid: contract.craftid
								});
							}
						}

						// Render contracts
						let contractsHtml = "";
						let i = 0;

						for (const [key, contract] of Object.entries(contractsMap)) {
							const head = `<div class="contract_head inner-left-sm inner-right-sm ${returnBackground(i)}" data-toggle="collapse" data-target=".contract_info${i}">
                                <div class='sorting-icon'><div class='fa fa-lg fa-arrows-v'></div></div>
                                <span class="contract-name">${key}</span>
                                <div class='collapse-caret'><div class='fa fa-lg fa-chevron-down'></div></div>
                                <span class="contract-heli pull-right">Helicopters (<span class='copterCount'>${contract.crafts.length}</span>)</span>
                            </div>`;

							let body = `<div class="contract_info${i} more_info collapse inner-sm inner-left-sm inner-right-sm" data-pk='${contract.id}'>
                                <div class='errors'></div>
                                <div class='col-md-7 center-block'>
                                    <table class='table table-condensed table-bordered contract_table'>
                                        <thead><tr><th>Craft</th><th>Registration</th></tr></thead>
                                        <tbody>`;

							for (const craft of contract.crafts) {
								body += `<tr>
                                    <td class='crft'>${craft.craft}</td>
                                    <td class='cls'>${craft.class}</td>
                                    <td><button class='btn btn-warning fa fa-minus removeContractItem' data-pk='${craft.craftid}' data-contract='${contract.id}'></button></td>
                                </tr>`;
							}

							body += `</tbody></table></div>
                                <div class='col-md-3 center-block outer-top-xs'>
                                    <div class='lbl'>Contract Color</div>
                                    <input type='color' class='colorpicker form-control' value='${contract.color}'>
                                </div>
                                <div class='col-md-3 center-block outer-top-xs'>
                                    <button class='btn btn-danger form-control deleteContractBtn' data-pk='${contract.id}' data-name='${key}' data-head='.contract_info${i}'>Delete Contract</button>
                                </div>
                            </div>`;

							contractsHtml += `<div class='collapsible-group' data-pk='${contract.id}'>${head}${body}</div>`;
							i++;
						}

						$("#contracts").html(contractsHtml); // Render contracts
						setContractEvents(true); // Bind event listeners
					} else {
						console.error("Error: Contracts data is not an array");
						showNotification("error", "Invalid contracts data format.");
					}
				} catch (e) {
					console.error("Error parsing contracts data:", e);
					showNotification("error", "Error parsing contracts data.");
				}
			} else {
				console.error("Error fetching contracts:", result.message);
				showNotification("error", result.message || "Error fetching contracts.");
			}
		},
		error: function (xhr, status, error) {
			console.error("AJAX error:", error);
			showNotification("error", "AJAX error: " + error);
		}
	});
}

function loadCrafts(selectElementId) {
	$.ajax({
		type: "GET",
		url: "get_all_crafts.php",
		success: function (response) {
			if (response.success) {
				const crafts = response.crafts;
				let options = "";
				for (let i = 0; i < crafts.length; i++) {
					if (crafts[i] && crafts[i].id && crafts[i].craft_type) {
						options += "<option value='" + crafts[i].id + "'>" + crafts[i].craft_type + " - " + crafts[i].registration + "</option>";
					} else {
						console.warn("Craft data incomplete at index " + i + ":", crafts[i]);
					}
				}
				$("#newContractCraftSelect").html(options);
				$(".fullsched #craft-select").html(options);
			} else {
				console.error("Error fetching crafts: " + response.message);
				showNotification("error", "Error fetching crafts: " + response.message);
			}
		},
		error: function (xhr, status, error) {
			console.error("AJAX error fetching crafts:", error);
			showNotification("error", "AJAX error fetching crafts: " + error);
		}
	});
}

// This function load all pilots and show in the "Select Pilots" list in Add New Contract Modal, each time is load
function loadPilots() {
	$.ajax({
		type: "GET",
		url: "get_all_pilots.php",
		success: function (result) {
			if (result != "false") {
				try {
					var pilots = JSON.parse(result);

					if (pilots && Array.isArray(pilots)) {  // Ensure it's an array
						var options = "";
						for (var i = 0; i < pilots.length; i++) {
							if (pilots[i] && pilots[i].id && pilots[i].fullname) { // Safe check of the full data
								options += "<option value='" + pilots[i].id + "'>" + pilots[i].fullname + "</option>";
							} else {
								console.warn("Pilot data incomplete at index " + i + ":", pilots[i]);
							}
						}
						$("#newContractPilotSelect").html(options); // Populate the select element
					} else {
						console.error("Invalid data format from get_all_pilots.php: Not an array or is empty");
						showNotification("error", "Error fetching pilots: Invalid data format.");
					}
				} catch (e) {
					console.error("Error parsing JSON:", e);
					showNotification("error", "Error parsing pilot data.");
				}

			} else {
				console.error("Error fetching pilots: get_all_pilots.php returned false");
				showNotification("error", "Error fetching pilots.");
			}
		},
		error: function (xhr, status, error) {
			console.error("AJAX error fetching pilots:", error);
			showNotification("error", "AJAX error fetching pilots: " + error);
		}
	});
}

$(document).on('click', '#submitNewContractBtn', function () {
	var contractName = $("#newContractName").val();
	var customerId = $("#newContractCustomerSelect").val();
	var craftIds = $("#newContractCraftSelect").val();
	var pilotIds = $("#newContractPilotSelect").val();
	var color = $("#newContractColor").val();

	if (!craftIds || craftIds.length === 0) {
		alert("Please select at least one craft.");
		return;
	}

	$.ajax({
		type: "POST",
		url: "add_contract.php",
		dataType: "json",
		data: {
			contract_name: contractName,
			customer_id: customerId,
			craft_ids: craftIds,
			pilot_ids: pilotIds,
			color: color
		},
		success: function (response) {
			console.log("AJAX Response:", response);
			if (response.success) {
				alert("Contract added successfully!");
				window.location.reload();
			} else {
				console.error("Error adding contract:", response.message);
				alert("Error adding contract: " + response.message);
			}
		},
		error: function (xhr, status, error) {
			console.error("AJAX error adding contract:", error);
			alert("AJAX error adding contract: " + error);
		}
	});
});

function setUpDeleteCustomer() {
	$(".deleteCustomerBtn").click(function () {
		var customerId = $(this).data("id");
		var customerName = $(this).closest("tr").find("td:first-child").text();

		// First, check if the customer has any associated contracts
		$.ajax({
			type: "POST",
			url: "check_customer_contracts.php",
			data: { customer_id: customerId },
			dataType: "json",
			success: function (response) {
				if (response.has_contracts) {
					// Customer has associated contracts, prevent deletion
					showNotification("error", "Cannot delete customer '" + customerName + "' because they have associated contracts. Please delete the contracts first.");
				} else {
					// Customer has no associated contracts, proceed with deletion
					if (confirm("Are you sure you want to delete " + customerName + "? This action cannot be undone!")) {
						deleteCustomer(customerId, customerName);  // Call a separate function for delete logic
					}
				}
			},
			error: function (xhr, status, error) {
				console.error("AJAX error checking customer contracts:", error);
				showNotification("error", "AJAX error checking customer contracts.  Please try again.");
			}
		});
	});
}

function deleteCustomer(customerId, customerName) {
	$.ajax({
		type: "POST",
		url: "delete_customer.php",
		data: { customer_id: customerId },
		dataType: "json",
		success: function (response) {
			if (response.success) {
				showNotification("success", "Customer deleted successfully.");
				loadCustomerList(); // Reload the customer table
			} else {
				showNotification("error", "Error deleting customer: " + response.message);
			}
		},
		error: function (xhr, status, error) {
			console.error("AJAX error deleting customer:", error);
			showNotification("error", "AJAX error deleting customer: " + error);
		}
	});
}

$(document).ready(function () {
	// Load contract list for the select element
	loadContractListSelect();
	loadCrafts();
	loadContractList();

});

// This function capitalize the letters of the string
function capitalize(str) {
	return str.charAt(0).toUpperCase() + str.slice(1);
}