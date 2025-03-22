function getTestName(fieldname) {
	switch (fieldname) {
		case "for_lic": return "Foreigner License";
			break;
		case "ang_lic": return accountNationality + " License";
			break;
		case "passport": return "Passport";
			break;
		case "ang_visa": return accountNationality + " Visa";
			break;
		case "us_visa": return "US Visa";
			break;
		case "instruments": return "Instrument Val";
			break;
		case "med": return "Medical";
			break;
		case "booklet": return logbookName;
			break;
		case "sim": return "Simulator"
			break;
		case "train_rec": return "Training Rec.";
			break;
		case "flight_train": return "Flight Training";
			break;
		case "base_check": return "Base Check";
			break;
		case "night_cur": return "Night Rig Currency";
			break;
		case "night_check": return "Night Check";
			break;
		case "ifr_cur": return "I.F.R Currency";
			break;
		case "ifr_check": return "I.F.R Check";
			break;
		case "line_check": return "Line Check";
			break;
		case "hoist_check": return "HOIST";
			break;
		case "hoist_cur": return "HOIST";
			break;
		case "crm": return "C.R.M";
			break;
		case "hook": return "HOOK";
			break;
		case "herds": return "HERDS";
			break;
		case "dang_good": return "Dangerous Goods";
			break;
		case "huet": return "HUET";
			break;
		case "english": return "English Level"
			break;
		case "faids": return "First Aid Training";
			break;
		case "fire": return "Basic Fire Fighting";
			break;
		case "avsec": return "AVSEC";
			break;
	}
}
function returnSecondsFromHHMM(time) {
	var t = time.split(":");
	hrs = parseInt(t[0]);
	min = parseInt(t[1]);
	return ((hrs * 3600) + (min * 60));
}
function returnSecondsFromHHMMSS(time) {
	var t = time.split(":");
	hrs = parseInt(t[0]);
	min = parseInt(t[1]);
	sec = parseInt(t[2]);
	return ((hrs * 3600) + (min * 60) + sec);
}
function returnDate(n) {
	switch (n) {
		case 0: return "January";
		case 1: return "February";
		case 2: return "March";
		case 3: return "April";
		case 4: return "May";
		case 5: return "June";
		case 6: return "July";
		case 7: return "August";
		case 8: return "September";
		case 9: return "October";
		case 10: return "November";
		case 11: return "December";
	}
}
function returnDateNumber(month) {
	switch (month) {
		case "January": return 0;
		case "February": return 1;
		case "March": return 2;
		case "April": return 3;
		case "May": return 4;
		case "June": return 5;
		case "July": return 6;
		case "August": return 7;
		case "September": return 8;
		case "October": return 9;
		case "November": return 10;
		case "December": return 11;
	}
}
function returnAbrvMonth(n) {
	switch (n) {
		case 0: return "Jan";
		case 1: return "Feb";
		case 2: return "Mar";
		case 3: return "Apr";
		case 4: return "May";
		case 5: return "Jun";
		case 6: return "Jul";
		case 7: return "Aug";
		case 8: return "Sept";
		case 9: return "Oct";
		case 10: return "Nov";
		case 11: return "Dec";
	}
}
function returnDOW(type, n) {
	switch (n) {
		case 6:
			if (type == "full") {
				return "Sunday";
			} else if (type == "abv") {
				return "Sun";
			}
		case 0:
			if (type == "full") {
				return "Monday";
			} else if (type == "abv") {
				return "Mon";
			}
		case 1:
			if (type == "full") {
				return "Tuesday";
			} else if (type == "abv") {
				return "Tue";
			}
		case 2:
			if (type == "full") {
				return "Wednesday";
			} else if (type == "abv") {
				return "Weds";
			}
		case 3:
			if (type == "full") {
				return "Thursday";
			} else if (type == "abv") {
				return "Thurs";
			}
		case 4:
			if (type == "full") {
				return "Friday";
			} else if (type == "abv") {
				return "Fri";
			}
		case 5:
			if (type == "full") {
				return "Saturday";
			} else if (type == "abv") {
				return "Sat";
			}
	}
}
function returnIndicator(n) {
	var ends = {
		'1': 'st',
		'2': 'nd',
		'3': 'rd'
	}
	n += ends[parseInt(n, 10) % 10] || 'th';
	return n;
}
function getMonday(type, date) {
	if (date != undefined) {
		today = date;
	} else {
		today = new Date();
	}
	dow = today.getDay();
	dow--;
	dow = (dow == -1 ? 6 : dow);
	offset = dow * 86400000;
	monday = new Date(today.getTime() - offset);

	switch (type) {
		case "string":
			return monday.getFullYear() + "-" + (monday.getMonth() + 1) + "-" + doubleDigit(monday.getDate());
			break;
		case "user-string":
			return monday.getFullYear() + "-" + returnDate(monday.getMonth()) + "-" + doubleDigit(monday.getDate());
			break;
		case "date":
			return monday;
			break;
	}
}
function getSunday(type, date) {
	if (date != undefined) {
		today = date;
	} else {
		today = new Date();
	}
	dow = today.getDay();
	if (dow != 0) {
		dow = 7 - dow;
	}
	offset = dow * 86400000;
	sunday = new Date(today.getTime() + offset);

	switch (type) {
		case "string":
			return sunday.getFullYear() + "-" + (sunday.getMonth() + 1) + "-" + doubleDigit(sunday.getDate());
			break;
		case "user-string":
			return sunday.getFullYear() + "-" + returnDate(sunday.getMonth()) + "-" + doubleDigit(sunday.getDate());
			break;
		case "date":
			return sunday;
			break;
	}
}
function returnTimeFromHours(hours) {
	var days = parseInt((hours / 24));
	var hrs = parseInt(hours - (days * 24));
	var mins = parseFloat((hours - (days * 24) - hrs) * 60).toFixed(2);
	if ((days + hrs + mins) > 0)
		return (days > 0 ? days + " day" + (days > 1 ? "s" : "") + " " : "") + (hrs > 0 ? hrs + " hour" + (hrs > 1 ? "s" : "") + " " : "") + (mins > 0 ? mins + " minute" + (mins > 1 ? "s" : "") : "");
	else
		return 0;
}

function roundDownDate(type, dateStr, strType) {
	dateStr = dateStr.split("-");
	datestr = dateStr[0] + "-" + doubleDigit(returnDateNumber(dateStr[1]) + 1) + "-" + dateStr[2];
	var d = new Date(datestr);
	switch (type) {
		case "week":
			return getMonday(strType, d);
			break;
		case "month":
			if (strType == "string")
				return d.getFullYear() + "-" + doubleDigit((d.getMonth() + 1)) + "-01";
			else if (strType == "user-string")
				return d.getFullYear() + "-" + returnDate(d.getMonth()) + "-01";
			break;
		case "year":
			if (strType == "string")
				return d.getFullYear() + "-01-01";
			else if (strType == "user-string")
				return d.getFullYear() + "-January-01";
			break;
		default:
			d = new Date();
			if (strType == "string")
				return d.getFullYear() + "-" + doubleDigit((d.getMonth() + 1)) + "-" + d.getDate();
			else if (strType == "user-string")
				return statsGraph(d.getFullYear() + "-" + returnDate(d.getMonth()) + "-" + d.getDate());
			break;
	}
}
var dates = {
	convert: function (d) {
		// Converts the date in d to a date-object. The input can be:
		//   a date object: returned without modification
		//  an array      : Interpreted as [year,month,day]. NOTE: month is 0-11.
		//   a number     : Interpreted as number of milliseconds
		//                  since 1 Jan 1970 (a timestamp) 
		//   a string     : Any format supported by the javascript engine, like
		//                  "YYYY/MM/DD", "MM/DD/YYYY", "Jan 31 2009" etc.
		//  an object     : Interpreted as an object with year, month and date
		//                  attributes.  **NOTE** month is 0-11.
		return (
			d.constructor === Date ? d :
				d.constructor === Array ? new Date(d[0], d[1], d[2]) :
					d.constructor === Number ? new Date(d) :
						d.constructor === String ? new Date(d) :
							typeof d === "object" ? new Date(d.year, d.month, d.date) :
								NaN
		);
	},
	compare: function (a, b) {
		// Compare two dates (could be of any type supported by the convert
		// function above) and returns:
		//  -1 : if a < b
		//   0 : if a = b
		//   1 : if a > b
		// NaN : if a or b is an illegal date
		// NOTE: The code inside isFinite does an assignment (=).
		return (
			isFinite(a = this.convert(a).valueOf()) &&
				isFinite(b = this.convert(b).valueOf()) ?
				(a > b) - (a < b) :
				NaN
		);
	},
	inRange: function (d, start, end) {
		// Checks if date in d is between dates in start and end.
		// Returns a boolean or NaN:
		//    true  : if d is between start and end (inclusive)
		//    false : if d is before start or after end
		//    NaN   : if one or more of the dates is illegal.
		// NOTE: The code inside isFinite does an assignment (=).
		return (
			isFinite(d = this.convert(d).valueOf()) &&
				isFinite(start = this.convert(start).valueOf()) &&
				isFinite(end = this.convert(end).valueOf()) ?
				start <= d && d <= end :
				NaN
		);
	}
};
function returnMonthMoment(m, yr) {
	if (yr == undefined) {
		year = new Date().getFullYear();
	} else {
		year = yr;
	}
	return moment(year + "-" + doubleDigit((m + 1)) + "-01");
}
function doubleDigit(n) {
	if (n < 10) {
		return "0" + n;
	}
	return n;
}
String.prototype.capitalize = function () {
	return this.charAt(0).toUpperCase() + this.slice(1);
}
$(document).ready(function () {
	$(".copyrightDate").text(new Date().getFullYear());
	$(".mnav").click(function () {
		$(".sidebar").toggle("blind");
	})
	$("#user-dropdown").click(function () {
		$("#user-dropdown .dropdown-content").toggle("blind");
	})
	$("#clock-dropdown").click(function () {
		$("#clock-dropdown .dropdown-header").toggleClass("active")
		$("#clock-dropdown .dropdown-content").toggle("show");
	})
	$("#notification-dropdown").click(function () {
		$("#notification-bell").removeClass("ring");
		$("#notification-dropdown .dropdown-header").toggleClass("active")
		$("#notification-dropdown .dropdown-content").toggle("show");
	})
	//start checking for notifications
	checkNotifications()
	// updateLocalTime();
	resetUserClock();
});

$(window).resize(function () {
	if ($(window).width() >= 1175) {
		$(".sidebar").show();
	}
})
var localTime;
function updateLocalTime() {
	localtime = setInterval(function () {
		$("#localTime .timeVal").text(new Date().toTimeString().split(" ")[0]);
	}, 1000);
}
var userTime;
function updateUserTime() {
	clearInterval(userTime);
	clearInterval(localTime);
	userTime = setInterval(function () {
		utc = new Date().getTime();
		timestamp = utc + TZ;
		date = new Date(timestamp);
		dateStr = (date.getUTCHours() < 10 ? "0" + date.getUTCHours() : date.getUTCHours()) + ":" + (date.getUTCMinutes() < 10 ? "0" + date.getUTCMinutes() : date.getUTCMinutes()) + ":" + (date.getUTCSeconds() < 10 ? "0" + date.getUTCSeconds() : date.getUTCSeconds());
		$("#setTime .timeVal").text(dateStr);
		$("#localTime .timeVal").text(new Date().toTimeString().split(" ")[0]);
	}, 1000)
}
var TZ = 0;
function resetUserClock() {
	$.ajax({
		type: "GET",
		url: "assets/php/get_clock_settings.php",
		success: function (result) {
			if (result.charAt(0) == "{" || result.charAt(0) == "[") {
				res = JSON.parse(result);
				if (res.tz != "" && res.name != "") {
					var utc = new Date().getTime();
					TZ = parseInt(res.tz) * -60 * 1000;
					var timestamp = utc + TZ;
					$("#setTime").show();
					$("#setTime .lbl").text(res.name);
					date = new Date(timestamp);
					dateStr = (date.getUTCHours() < 10 ? "0" + date.getUTCHours() : date.getUTCHours()) + ":" + (date.getUTCMinutes() < 10 ? "0" + date.getUTCMinutes() : date.getUTCMinutes()) + ":" + (date.getUTCSeconds() < 10 ? "0" + date.getUTCSeconds() : date.getUTCSeconds());
					$("#setTime .timeVal").text(dateStr);
					updateUserTime();
				} else {
					updateLocalTime();
				}
			} else {
				updateLocalTime();
			}
		}
	})
}

// universalfunctions.js
function showNotification(type, text, timeout) {
	if (!window.noty) { // Check if noty exists
		// console.error("Noty not loaded!");
		alert(`${type}: ${text}`); // Fallback
		return;
	}

	noty({
		layout: "top",
		type: type,
		text: text,
		timeout: timeout ?? 10000,
		killer: true
	});
}

function sortScheduleList() {
	var c = $(".editable-input select option");
	c.sort(function (a, b) {
		if ($(a).text() > $(b).text()) {
			return 1;
		} else if ($(a).text() == "Comandante" || $(a).text() == "Piloto" || $(a).text() < $(b).text()) {
			return -1;
		}
		return 0;
	});
	$(".editable-input select").empty();
	$(".editable-input select").html(c);
	//make Comandante/Piloto first option
	headOption = $(".editable-input select option[value='0']").clone();
	$(".editable-input select option[value='0']").remove();
	$(".editable-input select").prepend(headOption);
	//unset value
	$(".editable-input select").val("");
}

function sortAlphabetically(sortElement, parent, headValue) {
	var c = sortElement;
	c.sort(function (a, b) {
		if ($(a).text() > $(b).text()) {
			return 1;
		} else if ($(a).text() < $(b).text()) {
			return -1;
		}
		return 0;
	});
	parent.empty();
	parent.html(c);
	if (headValue !== undefined) {
		headOption = parent.find("option[value='" + headValue + "']").clone();
		parent.find("option[value='" + headValue + "']").remove();
		parent.prepend(headOption);
		parent.children().first().prop("selected", true);
	}
}

function markColor(selector) {
	var colors = ["#4EE3D4", "#CCC", "#fff", "#999", "#44CEE3", "#ED6551", "#6DC97E", "#EDE468", "#F78F20"],
		min = 0,
		max = 8,
		usedColors = [],
		c,
		current = "",
		counter = 0;
	$("#legendList").html("");
	$(selector).each(function (i) {
		if (current != $(this).data("contract")) {
			current = $(this).data("contract");
			c = contractsColor[current];
			$(this).children("th").css("background-color", c);
			$("#legendList").append("<li><div class='legendColor' style='background-color: " + c + "'></div>" + current + "</li>");
		} else {
			$(this).children("th").css("background-color", c);
		}
	})
	if ($("#legendList").children().length == 0) {
		$("#legendList").html("<li>No Aircrafts Listed</li>");
	}

}
function checkNotifications() {
	setTimeout(function () {
		var page = window.location.pathname.substring(1, window.location.pathname.indexOf("."));
		if (page == "messaging" && $(".messaging-list-item.selected").length != 0) {
			selected = $(".messaging-list-item.selected").data("pk");
		} else {
			selected = null;
		}
		$.ajax({
			type: "GET",
			url: "assets/php/check_notifications.php",
			data: { page: page },
			success: function (result) {
				if (result.charAt(0) == "{" || result.charAt(0) == "[") {
					var res = JSON.parse(result);
					count = 0;
					str = "";
					for (var i = 0; i < res.length; i++) {
						noti = res[i];
						if (noti.type == "message" && page != "messaging") {
							str += "<div class='notification-item'><a href='messaging.php?thread_id=" + noti.pk + "'><div class='notification-content'><span class='fa fa-envelope'></span> New message from " + noti.title + "</div></a></div>";
						} else if (noti.type == "news") {
							str += "<div class='notification-item'><a href='news.php'><div class='notification-content'>" + noti.title + "</div></a></div>";
						}
						count++;
					}

					if (count != 0 && str != "") {
						if (str != $("#notification-dropdown .dropdown-content").html()) {
							$("#notification-bell").addClass("ring");
							$("#notification-dropdown .dropdown-content").html(str);
							$("#notification-number").text((count < 10 ? count : "9+")).show();
						}
					} else {
						$("#notification-number").text("").hide();
						$("#notification-bell").removeClass("ring");
						$("#notification-dropdown .dropdown-content").html("<div class='notification-item'><div class='notification-content'>You have no notifications</div></div>");
					}
					checkNotifications();
				}
			}
		})
	}, 1000);
}