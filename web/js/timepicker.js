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
