var editableCrafts = [], editableCraftsModels = [];
$(document).ready(function(){	
	$(".sidebar-list a[href='statistics.php'] li").addClass("active");
	$("body").addClass("body-bg");
	$(".tab").click(function(){
		if(!$(this).hasClass("disabled")){
			$(".tab-pane, .tab").removeClass("active");
			$(this).addClass("active");
			$(".tab-pane[data-tab='"+$(this).data("tab-toggle")+"']").addClass("active");
		}	
		if($(this).data("tab-toggle") == "graphs"){
			$(".view-change.active").trigger("click");
		}
	})

	$("#scroll-right-indicator").click(function(){
		$("#addHoursSection").scrollLeft($("#addHoursSection").width());
	})

	$("#scroll-left-indicator").click(function(){
		$("#addHoursSection").scrollLeft(0);
	})
	//get pilot info
	// $.ajax({
	// 	type: "POST",
	// 	url: "assets/php/get_pilot_info.php",
	// 	success: function(response){
	// 		if(response != "false" && response != "" && response != null){
	// 			var res = JSON.parse(response);
	// 			$("#username").text(res["fname"]+" "+res["lname"]);
	// 		}
	// 	}
	// });
	$.ajax({
		type: "GET",
		url: "assets/php/get_all_crafts.php",
		data: {distinct: true},
		success: function(result){
			if(result.charAt(0) == "{" || result.charAt(0) == "["){
				var res = JSON.parse(result);
				for(var i = 0; i < res.length; i++){
					$("#addAircraft").append("<optgroup label='"+res[i]+"'></optgroup>");
					$("#addCraft").append("<option value='"+res[i]+"'>"+res[i]+"</option>");
					editableCraftsModels.push({value: res[i], text: res[i]});
				}
				var crafts = res;
				$.ajax({
					type: "GET",
					url: "assets/php/get_all_crafts.php",
					success: function(result){
						if(result.charAt(0) == "{" || result.charAt(0) == "["){
							var res = JSON.parse(result);
							for(var i = 0; i < res.length; i++){
								$("#addAircraft optgroup[label='"+res[i].craft+"']").append("<option value='"+res[i].class+"'>"+res[i].class+"</option>")
							}
							for(var i = 0; i < crafts.length; i++){
								var children = [];
								for(var j = 0; j < res.length; j++){
									if(res[j].craft == crafts[i])
									children.push({value: res[j].class, text: res[j].class});
								}
								editableCrafts.push({text: crafts[i], children: children});
							}
						}
					}
				})
			}
		}
	})
	$("<div id='datatooltip'></div>").css({
		position: "absolute",
		display: "none",
		border: "1px solid #fdd",
		borderRadius: "3px",
		padding: "2px",
		"background-color": "#bbb",
		opacity: 0.80
	}).appendTo("#personalSection");

	//LOG MODAL
	$("#logEndDate").datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		weekStart: 1
	}).datepicker("setDate", new Date());

	$("#logStartDate").datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		weekStart: 1
	}).on("changeDate", function(e){
		$("#logEndDate").datepicker("setStartDate", e.date);
	}).datepicker("setDate", moment().subtract(28, "days").toDate());

	$("#logbookEndDate").datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		weekStart: 1
	}).datepicker("setDate", new Date());

	$("#logbookStartDate").datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		weekStart: 1
	}).on("changeDate", function(e){
		$("#logbookEndDate").datepicker("setStartDate", e.date);
	}).datepicker("setDate", moment().subtract(28, "days").toDate());
	

	$("#launchLog").click(function(){
		if($("#logEndDate").val() != "" && $("#logStartDate").val() != ""){
			var myWindow = window.open("print.php?print_type=monthly_report&start="+$("#logStartDate").val()+"&end="+$("#logEndDate").val(), "_blank");
		}else{
			showNotification("error", "Please select a begining and end date for your report.");
		}
	})

	$("#printLog").unbind("click").click(function(){
		if($("#logbookEndDate").val() != "" && $("#logbookStartDate").val() != ""){
			var myWindow = window.open("print.php?print_type=logbook&start="+$("#logbookStartDate").val()+"&end="+$("#logbookEndDate").val()+"&output="+$("#logbookFormat").val(), "_blank");
		}else{
			showNotification("error", "Please select a begining and end date for your log.");
		}
	});

	$("#log-date").datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		weekStart: 1
	}).datepicker("setDate", moment().subtract(28, "d").toDate()).on("changeDate", function(e){
		getLogbook(true);
	});

	getLogbook(true);

	$(".view-change").click(function(){
		$(".view-change").removeClass("active");
		$(this).addClass("active");
		if($(this).data("view") != "past7" && $(this).data("view") != "past28"){
			setupDatePicker();
			var curDate = $("#graphStartDate").val();
			$("#graphStartDate").prop("disabled", false).val(roundDownDate($(this).data("view"), curDate, "user-string"))
			statsGraph(roundDownDate($(this).data("view"), curDate, "string"));
		}else{
			var d = new Date();
			dateStr = d.getFullYear()+"-"+returnDate(d.getMonth())+"-"+doubleDigit(d.getDate());
			$("#graphStartDate").prop("disabled", true).val(dateStr);
			statsGraph(d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate());
		}	
	})

	$(".view-change[data-view='past7']").trigger("click");

	getExperience();

	$("#addExperience").click(function(){
		var aircraft = $("#addExperienceCraft").val(),
			position = $("#addExperiencePos").val(),
			hours = $("#addExperienceHours").val();

		if(aircraft != "" && hours != "" && !isNaN(parseInt(hours))){
			$.ajax({
				type: "POST",
				data: {aircraft: aircraft, position: position, hours: hours},
				url: "assets/php/add_experience.php",
				success: function(result){
					console.log(result);
					$("#addExperienceHours, #addExperienceCraft").val("");
					showNotification("success", "You successfully added the entry");
					getExperience();
				}
			})
		}
	})
});

function setUpHoursEdit(){
	$(".hourEditDate, .hourEditHour").editable("destroy");
	$(".hourEditDate").editable({
		url: "assets/php/update_hour_entry.php",
		type: "date",
		format: "yyyy-mm-dd",
		name: "date",
		datepicker: {
			weekStart: 1
		},
		ajaxOptions: {
			type: "POST",
			cache: false
		},
		success: function(response, newValue){
			var curDate = $("#graphStartDate").val();
			statsGraph(roundDownDate($(".view-change.active").data("view"), curDate, "string"));
			setTimeout(function(){
				sortByDate();
			}, 1500);
		}
	})
	$(".hourEditHour").editable({
		url: "assets/php/update_hour_entry.php",
		type: "text",
		name: "hours",
		ajaxOptions: {
			type: "POST",
			cache: false
		},
		success: function(response, newValue){
			var curDate = $("#graphStartDate").val();
			statsGraph(roundDownDate($(".view-change.active").data("view"), curDate, "string"));
		}
	})
	$(".hourEditModel").editable({
		url: "assets/php/update_hour_entry.php",
		type: "select",
		name: "craft",
		source: editableCraftsModels,
		ajaxOptions: {
			type: "POST",
			cache: false
		},
		emptytext: "No Model Selected"
	})
	$(".hourEditCraft").editable({
		url: "assets/php/update_hour_entry.php",
		type: "select",
		name: "aircraft",
		source: editableCrafts,
		ajaxOptions: {
			type: "POST",
			cache: false
		},
		emptytext: "No Registration Selected"
	})
	$(".hourEditCommand").editable({
		url: "assets/php/update_hour_entry.php",
		type: "text",
		name: "command",
		ajaxOptions: {
			type: "POST",
			cache: false
		},
		emptytext: "No Pilot Entered"
	});
	$(".hourEditCopilot").editable({
		url: "assets/php/update_hour_entry.php",
		type: "text",
		name: "copilot",
		ajaxOptions: {
			type: "POST",
			cache: false
		},
		emptytext: "No Pilot Entered"
	})
	$(".hourEditRoute").editable({
		url: "assets/php/update_hour_entry.php",
		type: "textarea",
		name: "route",
		ajaxOptions: {
			type: "POST",
			cache: false
		},
		emptytext: "No Route Entered"
	})
	$(".hourEditIFR").editable({
		url: "assets/php/update_hour_entry.php",
		type: "text",
		name: "ifr",
		ajaxOptions: {
			type: "POST",
			cache: false
		},
		emptytext: "No IFR Entered"
	})
	$(".hourEditActual").editable({
		url: "assets/php/update_hour_entry.php",
		type: "text",
		name: "actual",
		ajaxOptions: {
			type: "POST",
			cache: false
		},
		emptytext: "No Actual Entered"
	})
	$(".hourEditHourType").editable({
		url: "assets/php/update_hour_entry.php",
		type: "select",
		name: "hour_type",
		ajaxOptions: {
			type: "POST",
			cache: false
		},
		source: [{text: "Day", value: "day"},{text: "Night", value: "night"}],
		emptytext: "No Type Entered"
	})
	$(".deleteEntry").unbind("click").click(function(){
		var row = $(this).parents("tr");
		$.ajax({
			type: "POST",
			url: "assets/php/delete_hour_entry.php",
			data: {pk: $(this).data("pk")},
			success: function(result){
				if(result == "success"){
					row.remove();
					var curDate = $("#graphStartDate").val();
					statsGraph(roundDownDate($(".view-change.active").data("view"), curDate, "string"));
				}
			}
		})
	})
}

function sortByDate(){
	var c = $("#manageHoursSection tbody tr");
	c.sort(function(a,b){
		timeA = new Date($(a).find("span.hourEditDate").text()).getTime();
		timeB = new Date($(b).find("span.hourEditDate").text()).getTime();
		if(timeA > timeB){
			return 1;
		}else if(timeA < timeB){
			return -1;
		}
		return 0;	
	});

	$("#manageHoursSection tbody").empty();
	$("#manageHoursSection tbody").html(c);

	setUpHoursEdit();
}

function setupDatePicker(){
	var type = $(".view-change.active").data("view");
	$("#graphStartDate").unbind("changeDate").datepicker("remove");
	switch(type){
		case "week":
			//disable days of week except sunday
			$("#graphStartDate").datepicker({
				format: "yyyy-MM-dd",
				daysOfWeekDisabled: [2,3,4,5,6,0],
				autoclose: true,
				weekStart: 1
			}).on("changeDate", function(e){
				newValue = e.format(0, "yyyy-mm-dd");
				statsGraph(newValue);
			})
		break;
		case "month":
			$("#graphStartDate").datepicker({
				format: "yyyy-MM-dd",
				minViewMode: 1,
				autoclose: true,
				weekStart: 1
			}).on("changeDate", function(e){
				newValue = e.format(0, "yyyy-mm-dd");
				statsGraph(newValue);
			})
		break;
		case "year":
			$("#graphStartDate").datepicker({
				format: "yyyy-MM-dd",
				minViewMode: 2,
				autoclose: true,
				weekStart: 1
			}).on("changeDate", function(e){
				newValue = e.format(0, "yyyy-mm-dd");
				statsGraph(newValue);
			})
		break;
	}
}

function getLogbook(init){
	var page = ($(".page-number").length > 0 ? $(".page-number.selected").data("page") : 0);
	console.log(page);
	$.ajax({
		type: "GET",
		url: "assets/php/get_pilot_statistics.php",
		data: {page: page, start: $("#log-date").val(), init: init},
		success: function(result){
			console.log(result);
			if(result.charAt(0) == "{" || result.charAt(0) == "["){
				var res = JSON.parse(result);
				if(res.entries.length > 0){
					//create table and append to it
					
					var tableStr = '<table class="table table-striped table-bordered" '+(init == undefined ? 'style="display: none;"' : '')+'><thead><th class="text-center">Date</th><th class="text-center">Model</th><th class="text-center">Registration</th><th class="text-center">Pilot In Command</th><th class="text-center">Copilot</th><th class="text-center">Route</th><th class="text-center">IFR</th><th class="text-center">Approaches</th><th class="text-center">Hours</th>'+""/*<th class="text-center">Hour Type</th>*/+'</thead><tbody>';
					for(var i = 0; i < res.entries.length; i++){
						entry = res.entries[i];
						tableStr += "<tr><td class='text-center'><span class='hourEditDate' data-pk='"+entry.id+"'>"+entry.date+"</span></td>"+"<td class='text-center'><span class='hourEditModel' data-pk='"+entry.id+"'>"+entry.craft+"</span></td><td class='text-center'><span class='hourEditCraft' data-pk='"+entry.id+"'>"+entry.aircraft+"</span></td><td class='text-center'><span class='hourEditCommand' data-pk='"+entry.id+"'>"+entry.command+"</span></td><td class='text-center'><span class='hourEditCopilot' data-pk='"+entry.id+"'>"+entry.copilot+"</span></td><td class='text-center'><span class='hourEditRoute' data-pk='"+entry.id+"'>"+entry.route+"</span></td><td class='text-center'><span class='hourEditIFR' data-pk='"+entry.id+"'>"+entry.ifr+"</span></td><td class='text-center'><span class='hourEditActual' data-pk='"+entry.id+"'>"+entry.actual+"</span></td><td class='text-center'><span class='hourEditHour' data-pk='"+entry.id+"'>"+entry.hours+"</span> ("+entry.hour_type.capitalize()+")</td>"+ "" /*"<td class='text-center'><span class='hourEditHourType' data-pk='"+entry.id+"'>"+entry.hour_type.capitalize()+"</span></td>"*/+"<th class='text-center'><button class='btn btn-sm btn-danger deleteEntry' data-pk='"+entry.id+"' title=\"Delete\"><div class='fa fa-times'></div></button></tr>";
					}
					tableStr += "</tbody></table>";
					if(init == undefined){
						if($("#manageHoursSection table").length != 0){
							$("#manageHoursSection table").hide("slide", {direction: "left"}, 500, function(){
								$("#manageHoursSection").html(tableStr);
								setUpStatsEdit();
								setUpHoursEdit();
								$("#manageHoursSection table").show("slide", {direction: "right"});
							});
						}else{
							$("#manageHoursSection").html(tableStr);
							setUpStatsEdit();
							setUpHoursEdit();
							$("#manageHoursSection table").show("slide", {direction: "right"});
						}							
					}else{
						$("#manageHoursSection").html(tableStr);
						setUpStatsEdit();
						setUpHoursEdit();
					}
					setUpPages(parseInt(res.total), (init == undefined ? false : true));
				}else{
					//display no data message
					$("#manageHoursSection, #graphSection").html("<h2 class='text-center'>You have no entries from "+$("#log-date").val()+" forward.</h2>");
					setUpPages(0);
					setUpStatsEdit();
				}
			}
		}
	});	
}

function setUpStatsEdit(){
	$("#addDate").datepicker("remove").datepicker({
		format: "yyyy-mm-dd",
		autoclose: true,
		weekStart: 1
	}).datepicker("setDate", new Date());
	$("#addEntry").unbind("click").click(function(){
		var date = $("#addDate").val(),
			dayHours = $("#addDayHour").val(),
			nightHours = $("#addNightHour").val(),
			model = $("#addCraft").val(),
			craft = $("#addAircraft").val(),
			command = $("#addCommand").val(),
			copilot = $("#addCopilot").val(),
			route = $("#addRoute").val(),
			actualInstrument = ($("#addActualInstrument").val() == "" ? 0 : parseInt($("#addActualInstrument").val())),
			ifrInstrument = ($("#addIFRInstrument").val() == "" ? 0 : parseInt($("#addIFRInstrument").val()));
		username = $("#username").text().split(" ");
		if(command.toLowerCase().indexOf(username[0].toLowerCase()) != -1 || command.toLowerCase().indexOf(username[1].toLowerCase()) != -1){
			pos = "com";
		}else if(copilot.toLowerCase().indexOf(username[0].toLowerCase()) != -1 || copilot.toLowerCase().indexOf(username[1].toLowerCase()) != -1){
			pos = "pil";
		}else{
			pos = "pil";
		}
		console.log(pos)
		if(date != "" && (dayHours != "" || nightHours != "")){
			$.ajax({
				type: "POST",
				url: "assets/php/add_stats_entry.php",
				data: {date: date, dayHours: dayHours, nightHours: nightHours, model: model, craft: craft, command: command, copilot: copilot, route: route, actual: actualInstrument, ifr: ifrInstrument, pos: pos},
				success: function(result){
					console.log(result);
					if(result.substring(0,7) == "success"){
						getLogbook();
						getExperience();
						date = $("#addDate").val("");
						$("#addDayHour").val("");
						$("#addNightHour").val("");
						$("#addCommand").val("");
						$("#addCopilot").val("");
						$("#addRoute").val("");
						$("#addActualInstrument").val("");
						$("#addIFRInstrument").val("");
						// sortByDate();
						var curDate = $("#graphStartDate").val();
						statsGraph(roundDownDate($(".view-change.active").data("view"), curDate, "string"));
						showNotification("success", "You successfully added the entry.");
					}
				}
			})
		}
	});
}
function statsGraph(start){
	var type = $(".view-change.active").data("view");
	$.ajax({
		type: "GET",
		data: {type: type, start: start},
		url: "assets/php/get_stats_graph.php",
		success: function(result){
			if(result.charAt(0) == "{" || result.charAt(0) == "["){
				var res = JSON.parse(result);
				console.log(res);
				// $("#totalHours").text(returnTimeFromHours(res.total)+" ("+res.total+" Hour"+(res.total != 1 ? "s" : "")+")");
				$("#totalHours").text(res.total+" Hour"+(res.total != 1 ? "s" : ""))
				$("#totalHours").removeClass("alert-danger").addClass(returnHourClass(type, parseFloat(res.total)));
				var data = res.data;
				if(data.length > 0){
					//each day
					max = maxDay;
					if(type == "year"){
						//each month for a year (12 data points)
						max = max28;
					}
					$.plot("#graphSection", data, {
						series: {
							bars: {
								show: true,
								barWidth: 1,
								align: "center",
								label: {
									show: true,
									formatter: labelFormatter
								}
							}
						},
						grid: {
							hoverable: true
						},
						legend: {
							show: false
						},
						xaxis: {
							mode: "categories",
							tickLength: 1,
						},
						yaxis: {
							max: max,
						}
					})

					$("#graphSection").unbind("plothover").bind("plothover", function (event, pos, item) {
						if (item && $(".view-change.active").data("view") != "year") {
							$("#datatooltip").html((item.series.label == "" ? "No Aircraft Entered" : item.series.label))
								.css({top: item.pageY+5, left: item.pageX+5})
								.fadeIn(200);
						} else {
							$("#datatooltip").hide();
						}
					});
				}
			}
		}
	})
}

function labelFormatter(label, series){
	isMonth = $(".view-change.active").data("view") == "month";
	if(isMonth){
		lbl = label.split(" ");
		return lbl[0]+"<br/>"+lbl[1];
	}
}

function returnHourClass(type, hours){
	if(type == "past7"){
		if(hours > maxSeven)
			return "alert-danger";
	}else if(type == "past28"){
		if(hours > max28)
			return "alert-danger";
	}
	return "";
}

function setUpPages(n, reset){
	var page = (reset ? null : $(".page-number.selected").data("page"));
	$(".pages").empty()
	if(n > 18){
		//pages.show() + make however many pages
		var p = Math.ceil((n/18));
		var i;
		for(i = 0; i < p; i++){
			$(".pages").append("<div class='page-number display-inline' data-page='"+i+"'>"+(i+1)+"</div>");
		}
		$(".page-number").unbind("click").click(function(){
			$(".page-number").removeClass("selected");
			$(this).addClass("selected");
			getLogbook();
		})
		if(page == null)
			$(".page-number[data-page='"+(i-1)+"']").addClass("selected");
		else
			$(".page-number[data-page='"+page+"']").addClass("selected");
		
		$("#page-container").show()
	}else{
		//pages.hide()
		$("#page-container").hide()
	}
}

function getExperience(){
	$.ajax({
		type: "GET",
		url: "assets/php/get_craft_experience.php",
		success: function(result){
			if(result.charAt(0) == "{" || result.charAt(0) == "["){
				var res = JSON.parse(result);
				var table = "<table class='table table-striped table-bordered no-shadow'><thead><tr><th>Aircraft</th><th>Pilot In Command</th><th>Copilot</th><th>Total</th><th>Delete Entries</th></tr></thead><tbody>";
				count = 0;
				var totalHours = 0, totalCom = 0, totalPil = 0;
				$.each(res, function(craft, ar){
					table += "<tr><td>"+craft+"</td><td>"+(ar.com != undefined ? parseFloat(ar.com).toFixed(1) : "&nbsp;")+"</td><td>"+(ar.pil != undefined ? parseFloat(ar.pil).toFixed(1) : "&nbsp;")+"</td><td>"+parseFloat((ar.pil != undefined ? parseFloat(ar.pil) : 0)+(ar.com != undefined ? parseFloat(ar.com) : 0)).toFixed(1)+"</td><th class='text-center' style='width: 115px;'><button class='btn btn-danger deleteExperience' data-pk='"+craft+"' >Delete</button></th></tr>";
					count++;

					totalHours += (ar.pil != undefined ? parseFloat(ar.pil) : 0)+(ar.com != undefined ? parseFloat(ar.com) : 0);
					totalCom += (ar.com != undefined ? parseFloat(ar.com) : 0);
					totalPil += (ar.pil != undefined ? parseFloat(ar.pil) : 0)
				})
				if(count == 0){
					table += "<tr><td colspan='4'>There is no experience to show</td></tr>";
				}else{
					table += "<tr><td><strong>Totals</strong></td><td>"+(totalCom != 0 ? totalCom.toFixed(1) : "")+"</td><td>"+(totalPil != 0 ? totalPil.toFixed(1) : "")+"</td><td>"+(totalHours != 0 ? totalHours.toFixed(1) : "")+"</td><tr>";
				}
				table += "</tbody></table>";
				$("#experience-section").html(table);
				$(".deleteExperience").click(function(){
					var pk = $(this).data("pk");
					$("body").append("<div id='deleteExperienceDialog' title='Are you sure you want to delete the experience for "+pk+"?'><div>");
					$("#deleteExperienceDialog").dialog({
						modal: true,
						dragable: false,
						resizeable: false,
						width: 675,
						buttons: {
							Confirm: function(){
								$.ajax({
									type: "POST",
									url: "assets/php/delete_experience.php",
									data: {craft: pk},
									success: function(result){
										console.log(result);
										getExperience();
										$("#deleteExperienceDialog").dialog("close");
										if(result == "success")
											showNotification("success", "You have successfully deleted the entry");
										else
											showNotification("error", "Deleting the entry was unsuccessful");
									}
								})
							},
							Cancel: function(){
								$("#deleteExperienceDialog").dialog("close");
							}
						},
						close: function(){
							$("#deleteExperienceDialog").remove();
						}
					})
				})
			}
		}
	})
}