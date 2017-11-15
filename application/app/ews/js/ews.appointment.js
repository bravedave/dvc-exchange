/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	test:
		ews.appointment();
	*/
ews.appointment = function() {
  var j = {
    date : $('<input type="text" class="form-control" />'),
    start : $('<input type="text" class="form-control" />'),
    end : $('<input type="text" class="form-control" />'),
    location : $('<input type="text" class="form-control" />'),
    notes : $('<textarea class="form-control" />'),
    save : $('<button class="btn btn-primary">save</button>'),
  }

  return templation.load({template:'container'}).then( function( tmpl ) {
    tmpl.get().attr('title','appointment');

    var r = $('<div class="row" />').appendTo( tmpl.get());
    $('<div class="col-3 form-label">date</label>').appendTo(r)
    $('<div class="col-9 input-group" />')
      .append( j.date)
      .append( '<span class="input-group-addon"><i class="fa fa-calendar-o" /></span>')
      .appendTo(r);

    var r = $('<div class="row py-1" />').appendTo( tmpl.get());
    $('<div class="col-3 form-label">time</label>').appendTo(r)
    $('<div class="col-9 input-group" />')
      .append( j.start)
      .append( '<span class="input-group-addon"><i class="fa fa-minus" /></span>')
      .append( j.end)
      .appendTo(r);

    var r = $('<div class="row py-1" />').appendTo( tmpl.get());
    $('<div class="col-3 form-label">location</label>').appendTo(r)
    $('<div class="col-9" />')
      .append( j.location)
      .appendTo(r);

    var r = $('<div class="row py-1" />').appendTo( tmpl.get());
    $('<div class="col-3 form-label">notes</label>').appendTo(r)
    $('<div class="col-9" />')
      .append( j.notes)
      .appendTo(r);

    var r = $('<div class="row py-1" />').appendTo( tmpl.get());
    $('<div class="offset-3 col-9" />')
      .append( j.save)
      .appendTo(r);

    console.log( tmpl);

    _brayworth_.modal.call( tmpl.get());

  })

}
