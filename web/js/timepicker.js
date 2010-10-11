var initialized = false;
var timepicker;
var active;

function bindTimepicker(target)
{
  initializeTimepicker();
  var timeinput = $(target);
  
  timeinput.click(function() {
    var timeinput = $(this);
    active = timeinput;
    var offset = timeinput.offset();
    var val = timeinput.val();
    timepicker.css({
      left: offset.left,
      top: (offset.top + timeinput.outerHeight()),
      width: timeinput.width()
      }).show();
    var components = parseTime(val);
    if(components)
    {
      var hour = components[0];
      if(components[2] == 'PM' && hour < 12)
      {
        hour = Math.floor(hour) + 12;
      }
      var pos = hour * 2;
      var scroll = $(timepicker.children()[pos]);
      timepicker.scrollTop(0);
      timepicker.scrollTop(scroll.position().top);
    }
  });
  
  timeinput.blur(function() {
    var val = $(this).val();
    $(this).val(validateTime(val));
  });
  
}

function initializeTimepicker()
{
  if(!initialized)
  {
    var body = document.getElementsByTagName("body")[0];
    var timeDiv = document.createElement('div');
    timeDiv.id = "timepicker";
    body.appendChild(timeDiv);
    for (hour = 0; (hour < 24); hour++)
    {
      for (min = 0; (min < 60); min += 30)
      {
        var sel = document.createElement('div');
        sel.innerHTML = prettyTime(hour, min);
        sel.setAttribute('class', 'time-item');
        timeDiv.appendChild(sel);
      }
    }
    timepicker = $('#timepicker');
    
    timepicker.find('.time-item').click(function() {
      active.val(this.innerHTML);
      active.change();
      timepicker.hide();
    });
    
    $(document).mousedown(_checkExternalClick);
    $(document).keypress(function(){
      timepicker.hide();
    });
 
    initialized = true;
  } 
}

function _checkExternalClick(event) {
  var target = $(event.target);
  if (target.attr('id') != timepicker.attr('id') && target.attr('class') != 'time' && !target.hasClass('time-item'))
    timepicker.hide();
}

function parseTime(time)
{
  var components = time.match(/(\d\d?)(:\d\d?)?\s*(am|pm)?/i);
  if(components)
  {
    var hour = components[1];
    var min = components[2];
    var ampm = components[3] ? components[3].toUpperCase() : false;
    if (min)
    {
      min = min.substr(1);
    } else {
      min = '00';
    }
    if (min < 10)
    {
      min = Math.floor(min) + '0';
    }
    if (!ampm)
    {
      if (hour >= 8)
      {
        ampm = 'AM';
      } else {
        ampm = 'PM';
      }
    }
    return [hour, min, ampm];
  }
  return false;
}
function formatTime(components)
{
  if(components)
  {
    return components[0] + ':' + components[1] + components[2];
  }
  return '';
}
function validateTime(string)
{
  return formatTime(parseTime(string));
}

function prettyTime(hour, min)
{
  var ampm = 'AM';
  phour = hour;
  if (hour >= 12)
  {
    ampm = 'PM';
  }
  if (hour >= 13)
  {
    phour -= 12;
  }
  if (phour == 0)
  {
    phour = 12;
  }
  pmin = min;
  if (min < 10)
  {
    pmin = '0' + Math.floor(min);
  }
  return phour + ':' + pmin + ampm;
}

function timepicker2(selector, options)
{	
	if (typeof(options) == 'undefined')
	{
		options = {};
	}

	var optionClass = options['time-class'];

	if (typeof(optionClass) == 'undefined')
	{
		optionClass = 'time-item';
	}
	
	var minutesIncrement = options['minutes-increment'];
	if (typeof(minutesIncrement) == 'undefined')
	{
		minutesIncrement = 1;
	}
	
	var hoursIncrement = options['hours-increment'];
	if (typeof(hoursIncrement) == 'undefined')
	{
		hoursIncrement = 1;
	}
	
	var twentyFourHour = options['twenty-four-hour'];
	if (typeof(twentyFourHour) == 'undefined')
	{
		twentyFourHour = true;
	}

	$(selector).each(function() {
		var timeinput = $(this);
		var picker = $('<input />');
		var options;
		var id = 'timepicker-' + (Math.floor(Math.random() * 9999));
		var optionsId = 'options-' + (Math.floor(Math.random() * 9999));

		// progressively E-N-H-A-N-C-E
		if (!timeinput.hasClass(optionClass + '-enabled'))
		{
			replaceInput();
		}
	
		function replaceInput()
		{
			picker.attr({'id': id, 'autocomplete': 'off'});
			picker.val(getTime());
			
			options = $('<div />');
			options.attr('id', optionsId);
			options.hide();
			options.addClass('time-items');
			$('body').append(options);
		
			for (var hour = 0; (hour < 24); hour += hoursIncrement)
			{
				for (var min = 0; (min < 60); min += minutesIncrement)
				{
					var option = $('<div />');
					var timeStr = prettyTime(hour, min);
					option.addClass(optionClass);
					if (timeStr == picker.val())
					{
						option.addClass(optionClass + '-selected');
					}
					option.text(timeStr);
					option.click(function()
					{
						picker.val($(this).text());
						picker.change();
						options.hide();
					});
					options.append(option);
				}
			}
	
			picker.click(function() {
				var offset = picker.offset();
				options.css({
					'position': 'absolute',
					left: offset.left,
					top: (offset.top + picker.outerHeight()),
					height: 100 + 'px',
					overflow: 'auto'
					});
				options.show();
				
				time = parseTime(picker.val());
				if (time)
				{
					var index = time.hours;
					if (!twentyFourHour)
					{
						index = index * 2;
					}
					var scroll = $(options.children()[index]);
					options.scrollTop(0);
					options.scrollTop(scroll.position().top);
				}
			});
			
			picker.change(function()
			{
				commitToForm(picker.val());
			});
			picker.blur(function()
			{
				picker.change();
			});
			
			$(this).mousedown(_checkExternalClick);

			function _checkExternalClick(event) {
	 			var target = $(event.target);
	 			if ((target.attr('id') != options.attr('id')) && !(target.hasClass(optionClass)))
	    		{
		    		options.hide();
		    	}
			}



			function defaultSelection()
			{
				var selection = options.find('.' + optionClass + '-selected');
				if (selection.length == 0)
				{
					var first = $(options.children()[0]);
					first.addClass(optionClass + '-selected');
				}
				
				return false;
			}
			
			function commitSelection()
			{
				var selection = options.find('.' + optionClass + '-selected');
			
				picker.val(selection.text());
				picker.change();
				
				options.scrollTop(0);
				options.scrollTop(selection.position().top);
			}
			
			function nextSelection()
			{
				if (!defaultSelection())
				{
					var selected = options.find('.' + optionClass + '-selected');
					
					if (selected.next().length != 0)
					{
						selected.removeClass(optionClass + '-selected');
						var next = selected.next();
						next.addClass(optionClass + '-selected');
					}
				}
				commitSelection();
			}
			
			function previousSelection()
			{
				if (!defaultSelection())
				{
					var selected = options.find('.' + optionClass + '-selected');
					
					if (selected.prev().length != 0)
					{
						selected.removeClass(optionClass + '-selected');
						var prev = selected.prev();
						prev.addClass(optionClass + '-selected');
					}
				}
				commitSelection();
			}

			picker.keydown(function(event){    	  		
    	  		switch(event.keyCode)
    	  		{
    	  			case 40:
    	  				event.preventDefault();
    	  				nextSelection();
    	  				break;
    	  			case 38:
    	  				event.preventDefault();
    	  				previousSelection();
    	  				break;
    	  			default:
    	  				options.hide();
    	  		}
    	  		
    	  		event.stopPropagation();
    		});
	
			// insert the new picker and show it
			timeinput.addClass(optionClass + '-enabled');
			timeinput.wrapInner('<div class="a-hidden"></div>');
			timeinput.prepend(picker);
		}
				
		function commitToForm(text)
		{
			var time = parseTime(text);
			
			if (time)
			{
				var inputs = timeinput.find('select');
				$(inputs[0]).val(time.hours);
				$(inputs[1]).val(time.minutes);
			}
		}
		
		function getTime()
		{
			var inputs = timeinput.find('select');
			if ($(inputs[0]).val() == '')
			{
				$(inputs[0]).val('0');
			}
			if ($(inputs[1]).val() == '')
			{
				$(inputs[1]).val('0');
			}
			
			return prettyTime($(inputs[0]).val(), $(inputs[1]).val());
		}
		
		function parseTime(text, hand)
		{
			retVal = validateTimeString(text);
			
			if (!retVal) {
				return false;
			}
			
			if (hand == 'hours')
			{
				return retVal.hours;
			}
			
			if (hand == 'minutes')
			{
				return retVal.minutes;
			}

			return retVal;
		}
	
		function validateTimeString(time)
		{
			var components = time.match(/(\d\d?)(:\d\d?)?\s*(am|pm)?/i);
			if (components != null)
			{	
				timeArray = new Array();
				timeArray.push(Math.floor(components[1]));
				if (typeof(components[2]) == 'undefined')
				{
					components[2] = '0';
				}
				timeArray.push(Math.floor(components[2].replace(/^:/, '')));
				if (typeof(components[3]) == 'undefined')
				{
					timeArray.push('AM');
				}
				else
				{
					timeArray.push(components[3]);
				}
				
				if (timeArray[2].match(/pm/i) != null)
				{
					if (timeArray[0] != 12)
					{
						timeArray[0] = timeArray[0] + 12;
					}
				}
				else if (timeArray[0] == 12)
				{
					timeArray[0] = 0;
				}

				
				if ((timeArray[0] < 0) || (timeArray[0] > 23))
				{
					return false;
				}
				
				if ((timeArray[1] < 0) || (timeArray[1] > 59))
				{
					return false;
				}
				
				var retVal = {};
				retVal['hours'] = timeArray[0];
				retVal['minutes'] = timeArray[1];
				
				return retVal;
			}
			
			return false;
		}
	
		function prettyTime(hour, min)
		{	
		  var timeStr = '';
		  
		  if (!twentyFourHour)
		  {
		  	var suffix = 'AM';
		  	if (hour > 11)
		  	{
		  		suffix = 'PM';
		  	}
		  	if (hour > 12)
		  	{
		  		hour = hour - 12;
		  	}
		  	if (hour == 0)
		  	{
		  		hour = 12;
		  	}
		  	timeStr = ' ' + suffix;
		  }
		  
		  if (hour < 10)
		  {
		  	hour = '0' + hour;
		  }
		  if (min < 10)
		  {
		  	min = '0' + min;
		  }
		  
		  return hour + ':' + min + timeStr;
		}
	});
}
