
(function ($) {
	
	var MyCalendar = function (divId, options) {
		// Initializing global variables
		var me = this;
        me.currentDay = new Date().getDate();
        me.currentMonth = new Date().getMonth();
        me.currentYear = new Date().getFullYear();
        me.currentWeekDay = me.getWeekDay(me.currentDay, me.currentMonth, me.currentYear);
        me.selectedDay = me.currentDay;
        me.selectedWeekDay = me.currentWeekDay;
        me.selectedMonth = me.currentMonth;
        me.selectedYear = me.currentYear;
        me.divId = divId;
		me.weekdays = [
			'Lundi',
			'Mardi',
			'Mercredi',
			'Jeudi',
			'Vendredi',
			'Samedi',
			'Dimanche'
		];
		me.months = [
			'Janvier',
			'Fevrier',
			'Mars',
			'Avril',
			'Mai',
			'Juin',
			'Juillet',
			'Août',
			'Septembre',
			'Octobre',
			'Novembre',
			'Décembre'
		];
        me.options = options;
		console.log(me);
		me.init();
	};
	
	MyCalendar.prototype = {
		
		init : function () {
			this.displayTable();
		},
		displayTable : function () {
			this.divId.html('');
			
			var container = $('<div />').css('width', '100%');
			
			var headerContainer = $('<div />').addClass('col-md-12');
			
			var tableContainer = $('<div />').addClass('col-md-9');
			
			var commandeContainer = $('<div />').addClass('col-md-3');
			
			headerContainer.append(
				$('<div />').append(
					$('<button />').html('<').click(
						{me : this},
						function (event) {
							event.data.me.previousMonth();
						}
					)
				).append(
					$('<span />').html(this.months[this.selectedMonth])
				).append(
					$('<button />').html('>').click(
						{me : this},
						function (event) {
							event.data.me.nextMonth();
						}
					)
				)
			);
			
			var table = $('<table />').css('width', '100%').css('table-layout','fixed');
			var thead = $('<thead />');
			var tr = $('<tr />');
			for (var i = 0 ; i < this.weekdays.length ; i++) {
				tr.append(
					$('<th />').html(this.weekdays[i]).css('border', '1px solid #CCCCCC').css('text-align', 'center')
				);
			}
			thead.append(tr);
			table.append(thead);
			var firstDay = new Date();
			firstDay.setMonth(this.selectedMonth);
			firstDay.setDate(1);
			var firstDayOfWeek = firstDay.getDay()-1;
			if (firstDayOfWeek == -1) {
				firstDayOfWeek = 6;
			}
			firstDay.setDate(firstDay.getDate() - firstDayOfWeek);
			var tbody = $('<tbody />');
			for (var i = 0 ; i < 6 ; i++) {
				var tr = $('<tr />');
				for (var j = 0 ; j < 7 ; j++) {
					var td = $('<td />').html(firstDay.getDate()).css('height', '50px');
					if (firstDay.getMonth() != this.selectedMonth) {
						td.css('background-color', '#EEEEEE');
					} else if (this.selectedMonth == this.currentMonth && firstDay.getDate() == this.currentDay) {
						td.css('background-color', '#FCF8E3');
					} else {
						td.css('cursor', 'pointer').click(
							{day : firstDay.getDate(), month : firstDay.getMonth(), year : firstDay.getFullYear(), calendar : this},
							function (event) {
								event.data.calendar.selectDate(event.data.day, event.data.month, event.data.year);
							}
						);
					}
					if (firstDay.getMonth() == this.currentMonth && firstDay.getDate() == this.selectedDay) {
						td.css('border', '1px solid #FF0000');
					} else {
						td.css('border', '1px solid #CCCCCC');
					}
					tr.append(td);
					firstDay.setDate(firstDay.getDate()+1);
				}
				tbody.append(tr);
			}
			table.append(tbody);
			
			tableContainer.append(table);
			
			commandeContainer.append(
				$('<h2 />').html(this.weekdays[this.selectedWeekDay] + ' ' + this.selectedDay + ' ' + this.months[this.selectedMonth] + ' ' + this.selectedYear)
			).append(
				$('<form />').attr('method', 'post').attr('action', '?controler=compte&action=commande_search').append(
					$('<input />').val(this.options.adresse).attr('name', 'adresse')
				).append(
					$('<button />').addClass('btn btn-primary').attr('type', 'submit').html('commander')
				)
			);
			
			container.append(
				$('<div />').addClass('row').append(headerContainer).append(tableContainer).append(commandeContainer)
			)
			
			this.divId.append(container);
		},
		getWeekDay : function (day, month, year) {
			var date = new Date();
			date.setDate(day);
			date.setMonth(month);
			date.setYear(year);
			var dayOfWeek = date.getDay()-1;
			if (dayOfWeek == -1) {
				dayOfWeek = 6;
			}
			return dayOfWeek;
		},
		getSelectedWeekDay : function () {
			return this.getWeekDay(this.selectedDay, this.selectedMonth, this.selectedYear);
		},
		previousMonth : function () {
			if (this.selectedMonth == 0) {
				this.selectedMonth = 11;
				this.previousYear();
			} else {
				this.selectedMonth--;
			}
			this.displayTable();
		},
		nextMonth : function () {
			if (this.selectedMonth == 11) {
				this.selectedMonth = 0;
				this.nextYear();
			} else {
				this.selectedMonth++;
			}
			this.displayTable();
		},
		previousYear : function () {
			this.selectedYear--;
		},
		nextYear : function () {
			this.selectedYear++;
		},
		selectDate : function (day, month, year) {
			this.selectedDay = day;
			this.selectedMonth = month;
			this.selectedYear = year;
			this.selectedWeekDay = this.getSelectedWeekDay();
			this.displayTable();
		}
	};

    $.fn.MyCalendar = function (oInit) {
        return this.each(function () {
            return new MyCalendar($(this), oInit);
        });
    };

}(jQuery));