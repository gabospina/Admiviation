var classes = [], heliContracts = [], contractsColor = [], InitTimepicker = true;
$(document).ready(function () {
	$(".tab").click(function () {
		if (!$(this).hasClass("disabled")) {
			$(".tab-pane, .tab").removeClass("active");
			$(this).addClass("active");
			$(".tab-pane[data-tab='" + $(this).data("tab-toggle") + "']").addClass("active");
		}
	})
	$(".tab").first().trigger("click");
	$(".sidebar-list a[href='scala.php'] li").addClass("active");


	//messaging panel =======================================================================================

	var tomorrow = moment().add(1, "day");
	$("#sched_day, #message_day, #record_day").datepicker({
		autoclose: true,
		weekStart: 1,
		format: "yyyy-MM-dd"
	}).datepicker("setDate", tomorrow.toDate()).on("changeDate", function (e) {
		if ($(this).attr("id") == "message_day") {
			if (moment($("#sched_day").datepicker("getDate")).format("YYYY-MMMM-DD") != e.format("yyyy-MM-dd")) {
				$("#sched_day").datepicker("setDate", e.date);
			}
			if (moment($("#record_day").datepicker("getDate")).format("YYYY-MMMM-DD") != e.format("yyyy-MM-dd")) {
				$("#record_day").datepicker("setDate", e.date);
			}
		} else if ($(this).attr("id") == "sched_day") {
			if (moment($("#message_day").datepicker("getDate")).format("YYYY-MMMM-DD") != e.format("yyyy-MM-dd")) {
				$("#message_day").datepicker("setDate", e.date);
			}
			if (moment($("#record_day").datepicker("getDate")).format("YYYY-MMMM-DD") != e.format("yyyy-MM-dd")) {
				$("#record_day").datepicker("setDate", e.date);
			}
		} else if ($(this).attr("id") == "record_day") {
			if (moment($("#message_day").datepicker("getDate")).format("YYYY-MMMM-DD") != e.format("yyyy-MM-dd")) {
				$("#message_day").datepicker("setDate", e.date);
			}
			if (moment($("#sched_day").datepicker("getDate")).format("YYYY-MMMM-DD") != e.format("yyyy-MM-dd")) {
				$("#sched_day").datepicker("setDate", e.date);
			}
		}
		$("#recordTitleSpan").text(moment(e.date).format("MMMM Do YYYY"));
		updateDailySchedule();
		updateMessages();
		updateRecordList();
	});

	$("#recordTitleSpan").text(tomorrow.format("MMMM Do YYYY"))
	$("#sendNotiSMS").click(function () {
		var pilots = [];
		var crafts = {};
		var date = moment($("#sched_day").datepicker("getDate"));
		$(".fullsched tbody tr").each(function () {
			if ($(this).find(".notify-checkbox").prop("checked")) {
				com = $(this).find(".edit[data-pos='com']").editable("getValue", true);
				pil = $(this).find(".edit[data-pos='pil']").editable("getValue", true);
				craft = $(this).find("th").text();
				details = $(this).find(".editDetails").text().trim();
				contract = $(this).data("contract");
				if (details.indexOf("nbsp") != -1)
					details = "";
				if (com != "0" && com != null) {
					if (crafts[craft] == undefined) {
						crafts[craft] = {};
					}
					crafts[craft].com = com;
					crafts[craft].details = details;
					crafts[craft].contract = contract;
					pilots.push({ id: com, position: "commandante", craft: craft, date: date.format("MMM DD"), dbDate: date.format("YYYY-MM-DD"), details: details });
				}
				if (pil != "0" && pil != null) {
					if (crafts[craft] == undefined) {
						crafts[craft] = {};
					}
					crafts[craft].pil = pil;
					crafts[craft].details = details;
					crafts[craft].contract = contract;
					pilots.push({ id: pil, position: "piloto", craft: craft, date: date.format("MMM DD"), dbDate: date.format("YYYY-MM-DD"), details: details });
				}
			}
		})
		if (pilots.length != 0) {
			$.ajax({
				type: "POST",
				url: "send_sms_notifications.php",
				data: { pilots: JSON.stringify(pilots) },
				success: function (result) {
					console.log(result);
					showNotification("success", "Notifications have been sent!");
					updateMessages();
					var temp = {};
					var isMultiWindow = false;
					var i = 0;
					console.log(crafts);
					$.each(crafts, function (craft, val) {
						i++;
						temp[craft] = val;
						if (i == 8) {
							window.open("print.php?print_type=record_forms&date=" + date.format("YYYY-MM-DD") + "&crafts=" + JSON.stringify(temp), "_blank");
							temp = {};
							i = 0;
							isMultiWindow = true;
						}
					});
					console.log(i);
					if (i != 0) {
						console.log("getting in here");
						window.open("print.php?print_type=record_forms&date=" + date.format("YYYY-MM-DD") + "&crafts=" + JSON.stringify(temp), "_blank");
					}
				}
			})
		} else {
			showNotification("info", "There are no notifications to send");
		}
	})

	$("#search_messages").keyup(function () {
		val = $(this).val().toLowerCase();
		$("#messageTable tbody tr").each(function () {
			if ($(this).text().toLowerCase().indexOf(val) == -1) {
				$(this).hide();
			} else {
				$(this).show();
			}
		})
	});

	$("#deleteMessages").click(function () {
		var messages = [];
		$(".sms-checkbox:not(#sms-check-all):checked").each(function () {
			messages.push($(this).data("pk"));
		})
		if (messages.length != 0) {
			$.ajax({
				type: "POST",
				url: "delete_sms_messages.php",
				data: { messages: JSON.stringify(messages) },
				success: function (result) {
					// if(result == "success"){
					showNotification("success", "You successfully deleted the messages.");
					updateMessages();
					// }
				}
			})
		} else {
			showNotification("info", "No messages were selected");
		}
	});

	//schedule panel =======================================================================

	$.ajax({
		url: "get_contracts.php",
		type: "POST",
		success: function (data) {
			//make select dropdown with different contracts
			if (data != "false") {
				var res = JSON.parse(data);
				if (res["name"] != undefined) {
					for (var i = 0; i < res["name"].length; i++) {
						contractsColor[res["name"][i]] = res["color"][i];
						$("#contract-select, #pilotContracts select").append("<option value='" + res["name"][i] + "'>" + res["name"][i] + "</option>");
					}
				}
			}
			$("#contract-select").change(function () {
				updateDailySchedule();
			});
			//add checking for contract selected and which craft is selected
			$.ajax({
				url: "get_aircrafts.php",
				type: "POST",
				success: function (data) {
					if (data != "false") {
						var craftResult = JSON.parse(data);
						classes = craftResult["classes"];
						heliContracts = craftResult["contract"];
						var crafts = craftResult["crafts"];
						for (var i = 0; i < crafts.length; i++) {
							$("#craft-select").append("<option value='" + crafts[i] + "'>" + crafts[i] + "</option>");
							$("#pilot-record-aircraft").append("<optgroup label='" + crafts[i] + "'></optgroup>");
						}

						$("#craft-select").change(function () {
							updateDailySchedule();
							document.cookie = "lastHeliSelected=" + $(this).val() + "; expires=" + new Date(new Date().getTime() + (86400 * 1000 * 1000)).toString();
							$.ajax({
								url: "change_default_craft.php",
								data: { craft: $(this).val() },
								type: "POST",
								success: function (result) {

								}
							});
						});
						if (lastHeliSelected != undefined && lastHeliSelected != "") {
							$("#craft-select option[value='" + lastHeliSelected + "']").prop("selected", true);
						}
						$("#craft-select").trigger("change");
						updateMessages();

						//record panel ================================================================================
						$.ajax({
							url: "get_all_contracts.php",
							success: function (result) {
								if (result.charAt(0) == "{" || result.charAt(0) == "[") {
									var res = JSON.parse(result);
									for (var i = 0; i < res.length; i++) {
										$("#pilot-record-aircraft optgroup[label='" + res[i].craft + "']").append("<option value='" + res[i].class + "' data-contract='" + res[i].contract + "' data-contractid='" + res[i].contractid + "'>" + res[i].class + "</option>");
										$("#recordCraftList").append("<div class='col-md-3 no-padding no-margin'><div class='check-box selected recordCraftChecks' data-contract='" + res[i].contract + "' data-value='" + res[i].class + "' data-pk='" + res[i].craftid + "'>" + res[i].class + "<div class='check-mark'><div class='fa fa-check-square-o'></div></div></div></div>")
									}

									$(".recordCraftChecks").on("click", function () {
										$(this).toggleClass("selected");
										if ($(this).data("value") == "none") {
											if ($(this).hasClass("selected")) {
												$(".recordCraftChecks").not("[data-value='none']").addClass("selected");
											} else {
												$(".recordCraftChecks").not("[data-value='none']").removeClass("selected");
											}
										} else {
											$(".recordCraftChecks[data-value='none']").removeClass("selected");
										}
									})

									$("#pilot-record-aircraft").change(function () {
										var option = $(this).find("option[value='" + $(this).val() + "']");
										var contract = option.data("contract"),
											contractid = option.data("contractid");
										$("#record-contract").text(contract).attr("data-contractid", contractid);
									});
								}
							}
						});
					}
				}
			});
		}
	});
	//record panel ================================================================================

	//get pilots lists for drop down selection
	$.ajax({
		type: "GET",
		url: "get_all_pilots.php",
		success: function (result) {
			if (result.charAt(0) == "{" || result.charAt(0) == "[") {
				var res = JSON.parse(result);
				if (res.id !== undefined) {
					for (var i = 0; i < res.id.length; i++) {
						$("#record-piloto").append("<option value='" + res.id[i] + "'>" + res.fname[i].capitalize() + " " + res.lname[i].capitalize() + "</option>");
						if (parseInt(res.comandante[i]) == 1) {
							$("#record-comandante").append("<option value='" + res.id[i] + "'>" + res.lname[i].capitalize() + ", " + res.fname[i].capitalize() + "</option>");
						}
						sortAlphabetically($("#record-piloto option"), $("#record-piloto"), "default");
						sortAlphabetically($("#record-comandante option"), $("#record-comandante"), "default");
					}
				}
			}
		}
	});

	$("#recordFormsDate").datepicker({
		autoclose: true,
		weekStart: 1,
		format: "yyyy-MM-dd"
	}).datepicker("update", new Date());

	$("#getRecordForms").click(function () {
		date = moment($("#recordFormsDate").datepicker("getDate"));
		var selectedcrafts = [], notselectedcrafts = [];
		$(".recordCraftChecks[data-value!='none'].selected").each(function () {
			selectedcrafts.push($(this).data("pk"));
		});
		selectType = "selected";
		crafts = selectedcrafts;

		window.open("print.php?print_type=record_forms&date=" + date.format("YYYY-MM-DD") + "&selectType=" + selectType + "&crafts=" + JSON.stringify(crafts), "_blank");
	})
	updateRecordList();
});

function updateDailySchedule() {
	var date = moment($("#sched_day").datepicker("getDate"));
	$("#day").html(date.format("dddd") + "<br/>" + date.format("MMM DD"));
	now = new Date();
	var isPast = date.isBefore(now, "day");
	var isToday = date.isSame(now, "day");
	var contract = $("#contract-select").val();
	var craft = $("#craft-select").val();
	$.ajax({
		type: "POST",
		data: { contract: contract, craft: craft, start: date.format("YYYY-MM-DD"), end: date.format("YYYY-MM-DD"), details: "true" },
		url: "get_full_schedule.php",
		success: function (data) {
			if (data != "false") {
				$(".fullsched tbody").html("");
				var res = JSON.parse(data);
				var comStr, pilStr, str = "", classStr;
				classes = res["classes"];
				heliContracts = res["heliContracts"];
				var records = res["records"];
				var dates = [date.toDate()], j = 0, noCom, noPil;
				InitTimepicker = true;

				for (var i = 0; i < classes.length; i++) {
					var rowStr = "<tr data-contract='" + heliContracts[i] + "'>" + ([0, 2, 5].indexOf(ADMIN) == -1 ? "<td class='text-center' style='width: 4%;'><input type='checkbox' class='notify-checkbox'/></td>" : "") + "<th>" + classes[i].class + "</th>";

					comStr = "";
					pilStr = "";
					detailID = "dne";
					details = "&nbsp;";
					noCom = false;
					noPil = true;
					if (res["date"] != undefined) {
						for (var k = 0; k < res["date"].length; k++) {
							if (classes[i].class == res["craft"][k]) {
								detailID = res["detailID"][k];
								details = res["details"][k];
								if (res["pos"][k] == "com") {
									if ([0, 2, 5].indexOf(ADMIN) == -1 && !isPast) {
										data = "data-type='select' data-name=\"" + res["craft"][k] + "\" data-pk=\"" + dates[j].getFullYear() + "-" + (dates[j].getMonth() + 1) + "-" + dates[j].getDate() + "\" data-pos=\"com\" data-value=\"" + res["id"][k] + "\" data-source=\"assets/php/get_valid_pilots.php?type=com&date=" + dates[j].getFullYear() + "-" + (dates[j].getMonth() + 1) + "-" + dates[j].getDate() + "&contract=" + heliContracts[i] + "&craft=" + classes[i].class + "&tod=" + classes[i].tod + "\""
										cls = "edit"
									} else {
										cls = ""
										data = "data-value=\"" + res["id"][k] + "\"";
									}
									var tdClass = "blue-bg";
									comStr = "<div class='com'><a class='" + cls + "' " + data + "><strong>" + res["name"][k] + "</strong></a><br/></div>";
								} else if (res["pos"][k] == "pil") {
									if ([0, 2, 5].indexOf(ADMIN) == -1 && !isPast) {
										cls = "edit"
										data = "data-type='select' data-name=\"" + res["craft"][k] + "\" data-pk=\"" + dates[j].getFullYear() + "-" + (dates[j].getMonth() + 1) + "-" + dates[j].getDate() + "\" data-pos=\"pil\" data-value=\"" + res["id"][k] + "\" data-source=\"assets/php/get_valid_pilots.php?type=pil&date=" + dates[j].getFullYear() + "-" + (dates[j].getMonth() + 1) + "-" + dates[j].getDate() + "&contract=" + heliContracts[i] + "&craft=" + classes[i].class + "&tod=" + classes[i].tod + "\" ";
									} else {
										cls = ""
										data = "data-value=\"" + res["id"][k] + "\"";
									}
									pilStr = "<div class='pil'><a class='" + cls + "' " + data + "><strong>" + res["name"][k] + "</strong></a></div>";
								}
							}
						}
					}

					if (comStr == "") {
						if ([0, 2, 5].indexOf(ADMIN) == -1 && !isPast) {
							data = "data-type='select' data-name=\"" + classes[i].class + "\" data-pk=\"" + dates[j].getFullYear() + "-" + (dates[j].getMonth() + 1) + "-" + dates[j].getDate() + "\" data-pos=\"com\" data-source=\"assets/php/get_valid_pilots.php?type=com&date=" + dates[j].getFullYear() + "-" + (dates[j].getMonth() + 1) + "-" + dates[j].getDate() + "&contract=" + heliContracts[i] + "&craft=" + classes[i].class + "&tod=" + classes[i].tod + "\" ";
							cls = "edit"
						} else {
							data = "";
							cls = ""
						}
						tdClass = "dark-bg";
						comStr = "<div class='com'><a class='" + cls + "' " + data + ">Comandante</a></br></div>";
						noCom = true;
					}
					if (pilStr == "") {
						if ([0, 2, 5].indexOf(ADMIN) == -1 && !isPast) {
							cls = "edit"
							data = "data-type='select' data-name=\"" + classes[i].class + "\" data-pk=\"" + dates[j].getFullYear() + "-" + (dates[j].getMonth() + 1) + "-" + dates[j].getDate() + "\" data-pos=\"pil\" data-source=\"assets/php/get_valid_pilots.php?type=pil&date=" + dates[j].getFullYear() + "-" + (dates[j].getMonth() + 1) + "-" + dates[j].getDate() + "&contract=" + heliContracts[i] + "&craft=" + classes[i].class + "&tod=" + classes[i].tod + "\"";
						} else {
							data = "";
							cls = ""
						}
						pilStr = "<div class='pil'><a class='" + cls + "' " + data + ">Piloto</a></div>";
						noPil = true;
					}
					rowStr += "<td class='" + tdClass + "'>" + comStr + pilStr + "</td><td colspan='2' style='vertical-align: bottom;'><p class='editDetails' data-pk='" + detailID + "' data-name='" + classes[i].class + "'>" + details + "</p></td>";

					//if shift could have already happened, allow entry of record. otherwise, display N/A
					if ([0, 2, 5].indexOf(ADMIN) == -1 && (!noCom || !noPil) && (isPast || isToday)) {
						comrecordpk = "new";
						compunchin = "08:00";
						compunchout = "18:00";
						comdaily = "Not Entered";
						comflown = "Not Entered";
						comlandings = "Not Entered";
						pilrecordpk = "new";
						pilpunchin = "08:00";
						pilpunchout = "18:00";
						pildaily = "Not Entered";
						pilflown = "Not Entered";
						pillandings = "Not Entered";
						if (records[classes[i].class] != undefined) {
							if (records[classes[i].class].com != undefined) {
								comrecordpk = records[classes[i].class].com.id;
								compunchin = records[classes[i].class].com.punch_in;
								compunchout = records[classes[i].class].com.punch_out;
								comdaily = records[classes[i].class].com.daily;
								comflown = records[classes[i].class].com.flown;
								comlandings = records[classes[i].class].com.landings;
							}
							if (records[classes[i].class].pil != undefined) {
								pilrecordpk = records[classes[i].class].pil.id;
								pilpunchin = records[classes[i].class].pil.punch_in;
								pilpunchout = records[classes[i].class].pil.punch_out;
								pildaily = records[classes[i].class].pil.daily;
								pilflown = records[classes[i].class].pil.flown;
								pillandings = records[classes[i].class].pil.landings;
							}
						}
						rowStr += "<td><input type='text' data-pk='" + comrecordpk + "' class='form-control recordData' data-name='punch_in' data-pos='com'><input type='text' data-pk='" + pilrecordpk + "' data-name='punch_in' data-pos='pil' class='form-control recordData' ></td>";
						rowStr += "<td><input type='text' data-pk='" + comrecordpk + "' class='form-control recordData' data-name='punch_out' data-pos='com'><input type='text' data-pk='" + pilrecordpk + "' data-name='punch_out' data-pos='pil' class='form-control recordData'></td>";
						rowStr += "<td><input type='text' data-pk='" + comrecordpk + "' class='form-control recordData' data-name='daily' data-pos='com' value='" + comdaily + "'><input type='text' data-pk='" + pilrecordpk + "' data-name='daily' data-pos='pil' class='form-control recordData' value='" + pildaily + "'></td>";
						rowStr += "<td><input type='text' data-pk='" + comrecordpk + "' class='form-control recordData' data-name='flown' data-pos='com' value='" + comflown + "'><input type='text' data-pk='" + pilrecordpk + "' data-name='flown' data-pos='pil' class='form-control recordData' value='" + pilflown + "'></td>";
						rowStr += "<td><input type='text' data-pk='" + comrecordpk + "' class='form-control recordData' data-name='landings' data-pos='com' value='" + comlandings + "'><input type='text' data-pk='" + pilrecordpk + "' data-name='landings' data-pos='pil' class='form-control recordData' value='" + pillandings + "'></td>";
						rowStr += "<td><button class='btn btn-success submitRecord'>Submit</button></td>";
					} else {
						rowStr += "<td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td>"
					}
					rowStr += "</tr>";

					$(".fullsched tbody").append(rowStr);
					if ([0, 2, 5].indexOf(ADMIN) == -1 && (!noCom || !noPil) && (isPast || isToday)) {
						//com  =================================================
						$(".fullsched tbody tr").eq(i).find(".recordData[data-name='punch_in'][data-pos='com'], .recordData[data-name='punch_out'][data-pos='com']").timepicker({
							minuteStep: 1,
							showMeridian: false,
						}).on("show.timepicker", function (e) {
							$(".icon-chevron-down").removeClass("icon-chevron-down").addClass("fa fa-chevron-down");
							$(".icon-chevron-up").removeClass("icon-chevron-up").addClass("fa fa-chevron-up");
						}).on("changeTime.timepicker", function (e) {
							if (!InitTimepicker) {
								if ($(this).parents("tr").find(".recordData[data-name='punch_in'][data-pos='com']").val() != "" && $(this).parents("tr").find(".recordData[data-name='punch_out'][data-pos='com']").val() != "") {
									var hours = ((returnSecondsFromHHMM($(this).parents("tr").find(".recordData[data-name='punch_out'][data-pos='com']").val()) - returnSecondsFromHHMM($(this).parents("tr").find(".recordData[data-name='punch_in'][data-pos='com']").val())) / 3600).toFixed(1);
									$(this).parents("tr").find(".recordData[data-name='daily'][data-pos='com']").val(hours);
								}
							}
						})

						//pil ==================================================
						$(".fullsched tbody tr").eq(i).find(".recordData[data-name='punch_in'][data-pos='pil'], .recordData[data-name='punch_out'][data-pos='pil']").timepicker({
							minuteStep: 1,
							showMeridian: false,
							defaultTime: "18:00"
						}).on("show.timepicker", function (e) {
							$(".icon-chevron-down").removeClass("icon-chevron-down").addClass("fa fa-chevron-down");
							$(".icon-chevron-up").removeClass("icon-chevron-up").addClass("fa fa-chevron-up");
						}).on("changeTime.timepicker", function (e) {
							if (!InitTimepicker) {
								if ($(this).parents("tr").find(".recordData[data-name='punch_in'][data-pos='pil']").val() != "" && $(this).parents("tr").find(".recordData[data-name='punch_out'][data-pos='pil']").val() != "") {
									var hours = ((returnSecondsFromHHMM($(this).parents("tr").find(".recordData[data-name='punch_out'][data-pos='pil']").val()) - returnSecondsFromHHMM($(this).parents("tr").find(".recordData[data-name='punch_in'][data-pos='pil']").val())) / 3600).toFixed(1);
									$(this).parents("tr").find(".recordData[data-name='daily'][data-pos='pil']").val(hours);
								}
							}
						})

						$(".fullsched tbody tr").eq(i).find(".recordData[data-name='punch_out'][data-pos='com']").timepicker("setTime", compunchout);
						$(".fullsched tbody tr").eq(i).find(".recordData[data-name='punch_in'][data-pos='com']").timepicker("setTime", compunchin).trigger("changeTime.timepicker");
						$(".fullsched tbody tr").eq(i).find(".recordData[data-name='punch_out'][data-pos='pil']").timepicker("setTime", pilpunchout);
						$(".fullsched tbody tr").eq(i).find(".recordData[data-name='punch_in'][data-pos='pil']").timepicker("setTime", pilpunchin).trigger("changeTime.timepicker");
					}
				}

				markColor(".fullsched tbody tr");
				if ($(".fullsched tbody").children().length == 0) {
					$(".fullsched tbody").append("<tr><td colspan='8'>No Aircrafts available</td><tr>");
				}

				$(".edit").editable("destroy");
				if ([0, 2, 5].indexOf(ADMIN) == -1 && !isPast) {
					$(".edit").editable({
						url: "update_schedule.php",
						ajaxOptions: {
							type: "POST",
							cache: false
						},
						params: function (params) {
							params.pos = $(this).attr("data-pos");
							return params;
						},
						sourceCache: false,
						sourceOptions: {
							type: "GET",
							data: { strict: $("#strict-search").val() }
						},
						escape: false,
						success: function (result, newValue) {
							if (result.substr(0, 5) != "false") {

								updateDailySchedule();
							} else {
								showNotification("error", "An error occured");
							}
							//update user sched if need be
						},
						display: false
					}).on("shown", function () {
						sortScheduleList();
					});

					$(".editDetails").editable("destroy").editable({
						type: "text",
						url: "update_schedule_details.php",
						ajaxOptions: {
							type: "POST",
							cache: false
						},
						mode: "inline",
						emptytext: "",
						params: function (params) {
							params.date = date.format("YYYY-MM-DD");
							return params;
						},
						success: function (response, newValue) {
							if (response.substring(0, 7) == "success") {
								updateDailySchedule();
							}
						},
						validate: function (value) {
							if ($.trim(value) == '' || value == String.fromCharCode(160)) {
								return "Please enter details or cancel.";
							}
						}
					}).on("shown", function (e, editable) {
						if ($(this).parent().width() > 275) {
							editable.input.$input.width(parseInt($(this).parent().width()) - 130);
						} else {
							editable.input.$input.width(275);
						}

					});

					$(".notify-checkbox").unbind("click").click(function () {
						if ($(this).attr("id") == "check-all") {
							$(".notify-checkbox").prop("checked", $(this).prop("checked"));
						} else {
							$("#check-all").prop("checked", false);
						}
					})
					$("#check-all").prop("checked", false);
					$("#check-all").trigger("click");
				}
				if ([0, 2, 5].indexOf(ADMIN) == -1 && (isPast || isToday)) {
					InitTimepicker = false;

					$(".recordData").unbind("focus").focus(function () {
						if ($(this).val() == "Not Entered") {
							$(this).val("");
						}
					})
					$(".submitRecord").unbind("click").click(function () {
						var row = $(this).parents("tr");
						if (row.find(".com>a").text() != "Comandante" || row.find(".pil>a").text() != "Piloto") {
							var comIn = row.find(".recordData[data-name='punch_in'][data-pos='com']").val(),
								comOut = row.find(".recordData[data-name='punch_out'][data-pos='com']").val(),
								comDaily = parseFloat(row.find(".recordData[data-name='daily'][data-pos='com']").val()),
								comFlown = parseFloat(row.find(".recordData[data-name='flown'][data-pos='com']").val()),
								comLandings = parseInt(row.find(".recordData[data-name='landings'][data-pos='com']").val()),
								pilIn = row.find(".recordData[data-name='punch_in'][data-pos='pil']").val(),
								pilOut = row.find(".recordData[data-name='punch_out'][data-pos='pil']").val(),
								pilDaily = parseFloat(row.find(".recordData[data-name='daily'][data-pos='pil']").val()),
								pilFlown = parseFloat(row.find(".recordData[data-name='flown'][data-pos='pil']").val()),
								pilLandings = parseInt(row.find(".recordData[data-name='landings'][data-pos='pil']").val()),
								craft = row.find("th").text(),
								date = moment($("#sched_day").datepicker("getDate")).format("YYYY-MM-DD");

							if (row.find(".com .edit").length == 1) {
								var com = row.find(".edit[data-pos='com']").editable("getValue", true);
							} else {
								var com = row.find(".com>a").data("value");
							}
							if (row.find(".pil .edit").length == 1) {
								var pil = row.find(".edit[data-pos='pil']").editable("getValue", true);
							} else {
								var pil = row.find(".pil>a").data("value");
							}

							comCheck = (com != null && com != "0" && comIn != comOut && !isNaN(comDaily) && !isNaN(comFlown) && !isNaN(comLandings));
							pilCheck = (pil != null && pil != "0" && pilIn != pilOut && !isNaN(pilDaily) && !isNaN(pilFlown) && !isNaN(pilLandings));
							console.log(com != null, com != "0", comIn != comOut, !isNaN(comDaily), !isNaN(comFlown), !isNaN(comLandings))
							console.log(pil != null, pil != "0", pilIn != pilOut, !isNaN(pilDaily), !isNaN(pilFlown), !isNaN(pilLandings))
							//one of the tables is filled out
							if (craft != null) {
								if (comCheck || pilCheck) {
									var isDone = false;
									if (comCheck) {
										id = row.find(".recordData[data-name='punch_in'][data-pos='com']").data("pk");
										$.ajax({
											type: "POST",
											url: "add_scala_record.php",
											data: { pos: "com", recordID: id, pilot: com, "in": comIn, out: comOut, daily: comDaily, flown: comFlown, landings: comLandings, date: date, craft: craft },
											success: function (result) {
												if (!pilCheck || isDone) {
													updateRecordList();
													updateDailySchedule();
													showNotification("success", "You successfully entered the record.");
												} else {
													isDone = true;
												}
											}
										})
									}
									if (pilCheck) {
										id = row.find(".recordData[data-name='punch_in'][data-pos='pil']").data("pk");
										$.ajax({
											type: "POST",
											url: "add_scala_record.php",
											data: { pos: "pil", recordID: id, pilot: pil, "in": pilIn, out: pilOut, daily: pilDaily, flown: pilFlown, landings: pilLandings, date: date, craft: craft },
											success: function (result) {
												console.log(result);
												if (!comCheck || isDone) {
													updateRecordList();
													updateDailySchedule();
													showNotification("success", "You successfully entered the record.");
												} else {
													isDone = true;
												}
											}
										})
									}
								} else {
									showNotification("info", "Please fill out at least one of the tables.");
								}
							} else {
								showNotification("info", "Please select an aircraft first.");
							}
						} else {
							showNotification("info", "Please select a craft that has pilots scheduled.")
						}
					});
				}
			}
		}
	});
}

// =========  BELOW - Implement Dynamic Pilot List Updates =======================

$(document).ready(function () {
	// Object to track selected pilots for each day
	var selectedPilotsByDay = {
		mon: [],
		tue: [],
		wed: [],
		thu: [],
		fri: [],
		sat: [],
		sun: []
	};

	// Add event listeners to all pilot dropdowns
	$("select.comandante, select.piloto").change(function () {
		updatePilotDropdowns($(this), selectedPilotsByDay);
	});

	// Load pilots into the dropdowns initially
	loadPilots(selectedPilotsByDay);
});

function updatePilotDropdowns(selectedDropdown, selectedPilotsByDay) {
	// Get the selected pilot's ID and the day
	var selectedPilotId = selectedDropdown.val();
	var day = selectedDropdown.attr("class").split(" ")[0]; // e.g., "mon", "tue", etc.

	// Update the selected pilots list for the day
	if (selectedPilotId) {
		// Add the selected pilot to the list for the day
		if (!selectedPilotsByDay[day].includes(selectedPilotId)) {
			selectedPilotsByDay[day].push(selectedPilotId);
		}

		// Replace the dropdown with the selected pilot's name
		// var pilotName = selectedDropdown.find("option:selected").text();
		// selectedDropdown.replaceWith('<span class="selected-pilot">' + pilotName + '</span>');
	} else {
		// If no pilot is selected (empty option), remove the previously selected pilot from the list
		var previouslySelectedPilot = selectedDropdown.data("previous-value");
		if (previouslySelectedPilot) {
			selectedPilotsByDay[day] = selectedPilotsByDay[day].filter(function (pilotId) {
				return pilotId != previouslySelectedPilot;
			});
		}

		// Restore the dropdown
		$(".selected-pilot").replaceWith('<select class="' + day + ' comandante"><option value="">Select Pilot</option></select>');
	}

	// Store the current selected value for future reference
	selectedDropdown.data("previous-value", selectedPilotId);

	// Update all dropdowns for the same day
	updateDropdownsForDay(day, selectedPilotsByDay);
}

function updateDropdownsForDay1(day, selectedPilotsByDay) {
	// Get all dropdowns for the specified day
	$("select." + day).each(function () {
		var currentDropdown = $(this);
		var currentSelectedPilot = currentDropdown.val();

		// Clear the dropdown and add the default option
		// currentDropdown.empty().append('<option value="">Select Pilot</option>');

		// Get all pilots from the database (or cached list)
		$.ajax({
			type: "GET",
			url: "get_all_pilots.php",
			success: function (result) {
				if (result != "false") {
					// var pilots = JSON.parse(result);

					// Add pilots to the dropdown, excluding those already selected for the day
					pilots.forEach(function (pilot) {
						if (!selectedPilotsByDay[day].includes(pilot.id)) {
							currentDropdown.append('<option value="' + pilot.id + '">' + pilot.fullname + '</option>');
						}
					});

					// Restore the previously selected pilot (if any)
					if (currentSelectedPilot && !selectedPilotsByDay[day].includes(currentSelectedPilot)) {
						currentDropdown.val(currentSelectedPilot);
					}
				}
			}
		});
	});
}

function loadPilots1(selectedPilotsByDay) {
	// Get all pilots from the database (or cached list)
	$.ajax({
		type: "GET",
		url: "get_all_pilots.php",
		success: function (result) {
			if (result != "false") {
				var pilots = JSON.parse(result);

				// Populate all dropdowns with pilots, excluding those already selected for the day
				$("select.comandante, select.piloto").each(function () {
					var currentDropdown = $(this);
					var day = currentDropdown.attr("class").split(" ")[0]; // e.g., "mon", "tue", etc.
					var currentSelectedPilot = currentDropdown.val();

					// Clear the dropdown and add the default option
					// currentDropdown.empty().append('<option value="">Select Pilot</option>');

					// Add pilots to the dropdown, excluding those already selected for the day
					pilots.forEach(function (pilot) {
						if (!selectedPilotsByDay[day].includes(pilot.id)) {
							currentDropdown.append('<option value="' + pilot.id + '">' + pilot.fullname + '</option>');
						}
					});

					// Restore the previously selected pilot (if any)
					if (currentSelectedPilot && !selectedPilotsByDay[day].includes(currentSelectedPilot)) {
						currentDropdown.val(currentSelectedPilot);
					}
				});
			}
		}
	});
}

function updateDropdownsForDay2(day, selectedPilotsByDay) {
	// Get all dropdowns for the specified day
	$("select." + day).each(function () {
		var currentDropdown = $(this);
		var currentSelectedPilot = currentDropdown.val();

		// Clear the dropdown and add the default option
		currentDropdown.empty().append('<option value="">Select ' + (currentDropdown.hasClass('comandante') ? 'Comandante' : 'Piloto') + '</option>');

		// Get all pilots from the database (or cached list)
		$.ajax({
			type: "GET",
			url: "get_all_pilots.php",
			success: function (result) {
				try {
					var res = JSON.parse(result);
					if (res.success) {
						// Add pilots to the dropdown, excluding those already selected for the day
						res.pilots.forEach(function (pilot) {
							if (!selectedPilotsByDay[day].includes(pilot.id)) {
								currentDropdown.append('<option value="' + pilot.id + '">' + pilot.fullname + '</option>');
							}
						});

						// Restore the previously selected pilot (if any)
						if (currentSelectedPilot && !selectedPilotsByDay[day].includes(currentSelectedPilot)) {
							currentDropdown.val(currentSelectedPilot);
						}
					} else {
						console.error(res.message);
					}
				} catch (e) {
					console.error("Error parsing JSON response: ", e);
				}
			},
			error: function (xhr, status, error) {
				console.error("AJAX Error: ", status, error);
			}
		});
	});
}

function loadPilots2(selectedPilotsByDay) {
	// Get all pilots from the database
	$.ajax({
		type: "GET",
		url: "get_all_pilots.php",
		success: function (result) {
			try {
				// Parse the JSON response (simple array of pilots)
				var pilots = JSON.parse(result);

				// Loop through all dropdowns
				$("select.comandante, select.piloto").each(function () {
					var currentDropdown = $(this);
					var day = currentDropdown.attr("class").split(" ")[0]; // e.g., "mon", "tue", etc.

					// Clear the dropdown and add the default option
					currentDropdown.empty().append('<option value="">Select ' + (currentDropdown.hasClass('comandante') ? 'Comandante' : 'Piloto') + '</option>');

					// Add pilots to the dropdown, excluding those already selected for the day
					pilots.forEach(function (pilot) {
						if (!selectedPilotsByDay[day] || !selectedPilotsByDay[day].includes(pilot.id)) {
							currentDropdown.append('<option value="' + pilot.id + '">' + pilot.fullname + '</option>');
						}
					});
				});
			} catch (e) {
				console.error("Error parsing JSON response: ", e);
			}
		},
		error: function (xhr, status, error) {
			console.error("AJAX Error: ", status, error);
		}
	});
}

function updateDropdownsForDay(day, selectedPilotsByDay) {
	// Get all dropdowns for the specified day
	$("select." + day).each(function () {
		var currentDropdown = $(this);
		var currentSelectedPilot = currentDropdown.val();

		// Add a loading state to the dropdown
		currentDropdown.empty().append('<option value="">Loading...</option>');

		// Get all pilots from the database (or cached list)
		$.ajax({
			type: "GET",
			url: "get_all_pilots.php",
			success: function (result) {
				try {
					// Parse the JSON response (simple array of pilots)
					var pilots = JSON.parse(result);

					// Clear the dropdown and add the default option
					// currentDropdown.empty().append('<option value="">Select ' + (currentDropdown.hasClass('comandante') ? 'Comandante' : 'Piloto') + '</option>');
					currentDropdown.empty().append('<option value="">' + (currentDropdown.hasClass('comandante') ? 'PIC' : 'SIC') + '</option>');

					// Add pilots to the dropdown, excluding those already selected for the day
					pilots.forEach(function (pilot) {
						if (!selectedPilotsByDay[day] || !selectedPilotsByDay[day].includes(pilot.id)) {
							currentDropdown.append('<option value="' + pilot.id + '">' + pilot.fullname + '</option>');
						}
					});

					// Restore the previously selected pilot (if any)
					if (currentSelectedPilot && !selectedPilotsByDay[day].includes(currentSelectedPilot)) {
						currentDropdown.val(currentSelectedPilot);
					}
				} catch (e) {
					console.error("Error parsing JSON response: ", e);
					currentDropdown.empty().append('<option value="">Error loading pilots</option>');
				}
			},
			error: function (xhr, status, error) {
				console.error("AJAX Error: ", status, error);
				currentDropdown.empty().append('<option value="">Error loading pilots</option>');
			}
		});
	});
}

function loadPilots(selectedPilotsByDay) {
	// Get all pilots from the database
	$.ajax({
		type: "GET",
		url: "get_all_pilots.php",
		success: function (result) {
			try {
				// Parse the JSON response (simple array of pilots)
				var pilots = JSON.parse(result);

				// Loop through all dropdowns
				$("select.comandante, select.piloto").each(function () {
					var currentDropdown = $(this);
					var day = currentDropdown.attr("class").split(" ")[0]; // e.g., "mon", "tue", etc.

					// Clear the dropdown and add the default option
					currentDropdown.empty().append('<option value="">' + (currentDropdown.hasClass('comandante') ? 'PIC' : 'SIC') + '</option>');

					// Add pilots to the dropdown, excluding those already selected for the day
					pilots.forEach(function (pilot) {
						if (!selectedPilotsByDay[day] || !selectedPilotsByDay[day].includes(pilot.id)) {
							currentDropdown.append('<option value="' + pilot.id + '">' + pilot.fullname + '</option>');
						}
					});
				});
			} catch (e) {
				console.error("Error parsing JSON response: ", e);
				$("select.comandante, select.piloto").empty().append('<option value="">Error loading pilots</option>');
			}
		},
		error: function (xhr, status, error) {
			console.error("AJAX Error: ", status, error);
			$("select.comandante, select.piloto").empty().append('<option value="">Error loading pilots</option>');
		}
	});
}

//  ==== BELOW - THIS FORM CRAFTSFUNCTIONS.JS =====================

function loadPilots2(selectedPilotsByDay) {
	$.ajax({
		type: "GET",
		url: "get_all_pilots.php",
		success: function (result) {
			if (result != "false") {
				try {
					var pilots = JSON.parse(result);

					if (pilots && Array.isArray(pilots)) {  // Ensure it's an array
						// var options = "<option value=''>Select Pilot</option>";
						for (var i = 0; i < pilots.length; i++) {
							if (pilots[i] && pilots[i].id && pilots[i].fullname) { // Safe check of the full data
								options += "<option value='" + pilots[i].id + "'>" + pilots[i].fullname + "</option>";
							} else {
								console.warn("Pilot data incomplete at index " + i + ":", pilots[i]);
							}
						}

						// Populate all <select> elements with class "comandante" or "piloto"
						$("select.comandante, select.piloto").html(options);
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
// =========  ABOVE - Implement Dynamic Pilot List Updates =======================

function updateMessages() {
	var date = moment($("#sched_day").datepicker("getDate"));
	$.ajax({
		type: "GET",
		url: "get_sms_messages.php",
		data: { date: date.format("YYYY-MM-DD") },
		success: function (result) {
			if (result.charAt(0) == "{" || result.charAt(0) == "[") {
				var res = JSON.parse(result);
				var entries = "";
				for (var i = 0; i < res.length; i++) {
					message = res[i];
					entries += "<tr><td class='text-center' style='width: 4%;'><input type='checkbox' class='sms-checkbox' data-pk='" + message.sms_id + "'/></td><td>" + message.sched_date;
					entries += "</td><td>" + message.craft + "</td><td>" + message.name + "</td><td>" + moment(message.sent_date).format("YYYY-MM-DD H:mm:ss") + "</td><td style='background-color: " + returnStatusColor(message.status) + ";'>" + message.status.capitalize() + "</td></tr>";
				}
				if (entries == "") {
					entries = "<tr><td colspan='6'>No message result</td></tr>";
				}
				$("#messageTable tbody").html(entries);

				$(".sms-checkbox").unbind("click").click(function () {
					if ($(this).attr("id") == "sms-check-all") {
						$(".sms-checkbox").prop("checked", $(this).prop("checked"));
					} else {
						$("#sms-check-all").prop("checked", false);
					}
				});
				$("#sms-check-all").prop("checked", false);
				$("#sms-check-all").trigger("click");
			}
		}
	})
}

function returnStatusColor(status) {
	switch (status) {
		case "queued": return "#84D7E0"; break;
		case "sending": return "#8EC7ED"; break;
		case "sent": return "#8EA6ED"; break;
		case "receiving": return "#8EC7ED"; break;
		case "delivered": return "#70E68E"; break;
		case "undelivered": return "#E6B585"; break;
		case "failed": return "#E8A2A2"; break;
	}
}

function updateRecordList() {
	var date = moment($("#sched_day").datepicker("getDate"));
	$.ajax({
		type: "GET",
		url: "get_scala_records.php",
		data: { date: date.format("YYYY-MM-DD") },
		success: function (result) {
			console.log(result);
			if (result.charAt(0) == "{" || result.charAt(0) == "[") {
				var res = JSON.parse(result);
				var entries = "";
				for (var i = 0; i < res.length; i++) {
					entry = res[i];
					// entry.over7Daily = true;
					// entry.over365Daily = true;
					// entry.over28Flight = true;
					// entry.over365Flight = true;
					dailyLimits = (entry.overDaily ? "<span class='limit-overage'>1</span>" : "") + (entry.over7Daily ? "<span class='limit-overage'>7</span>" : "") + (entry.over28Daily ? "<span class='limit-overage'>28</span>" : "") + (entry.over365Daily ? "<span class='limit-overage'>365</span>" : "");
					dailyLimits = (dailyLimits == "" ? "None" : dailyLimits);
					flightLimits = (entry.overFlight ? "<span class='limit-overage'>1</span>" : "") + (entry.over7Flight ? "<span class='limit-overage'>7</span>" : "") + (entry.over28Flight ? "<span class='limit-overage'>28</span>" : "") + (entry.over365Flight ? "<span class='limit-overage'>365</span>" : "");
					flightLimits = (flightLimits == "" ? "None" : flightLimits);
					entries += "<tr><td>" + entry.craft + "</td><td>" + entry.pilot_name + "</td><td>" + returnFullPosition(entry.position) + "</td><td><span class='record-text-editable' data-name='punch_in' data-pk='" + entry.id + "'>" + entry.punch_in + "</span></td><td><span class='record-text-editable' data-name='punch_out' data-pk='" + entry.id + "'>" + entry.punch_out + "</span></td><td><span class='record-text-editable " + (entry.overDaily ? "alert-danger" : "") + "' data-name='daily' data-pk='" + entry.id + "'>" + entry.daily + "</span></td><td><span class='record-text-editable " + (entry.overFlight ? "alert-danger" : "") + "' data-name='flown' data-pk='" + entry.id + "'>" + entry.flown + "</span></td><td><span class='record-text-editable' data-name='landings' data-pk='" + entry.id + "'>" + entry.landings + "</span></td><td>" + dailyLimits + "</td><td>" + flightLimits + "</td><th><button class='btn btn-danger deleteRecord' data-pk='" + entry.id + "'><div class='fa fa-times'></div></button></th></tr>";
				}
				if (entries == "") {
					entries = "<tr><td colspan='67'>No records available</td></tr>";
				}
				$("#recordTable tbody").html(entries);

				$(".record-text-editable").editable({
					type: "text",
					url: "update_scala_records.php",
					ajaxOptions: {
						type: "POST",
						cache: false
					},
					success: function (response, newValue) {
						if (response == "success") {
							showNotification("success", "You successfully updated the entry.");
							console.log("reload graphs");
						} else {
							showNotification("error", "Updating the entry was unsuccessful.");
						}
					}
				});

				$(".deleteRecord").click(function () {
					var row = $(this).parents("tr");
					$.ajax({
						type: "POST",
						data: { id: $(this).data("pk") },
						url: "delete_scala_record.php",
						success: function (result) {
							if (result == "success") {
								updateRecordList();
								updateDailySchedule();
								showNotification("success", "You successfully deleted the entry.");
							} else {
								showNotification("error", "Deleting the entry was unsuccessful.");
							}
						}
					})
				})
			}
		}
	})
}

function returnFullPosition(pos) {
	if (pos.toLowerCase() == "com") {
		return "Comandante";
	} else if (pos.toLowerCase() == "pil") {
		return "Piloto";
	}
}