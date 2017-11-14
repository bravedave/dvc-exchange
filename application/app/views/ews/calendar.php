<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/ ?>
<div class="container-fluid" id="plannerbox"></div>

<script>
$(document).ready( function() {
		var seed = _brayworth_.moment();
		seed.add( 1, 'days')
    ews.calendar.day({ host : '#plannerbox', seed : seed });

})
</script>
