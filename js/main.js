(function( root, $, undefined ) {
	"use strict";

	$(function () {




		function loadBallot(callbackFunction) {
			var ballotContainer = $('#practiceBallot');
			if(ballotContainer.length > 0) {
				ballotContainer.html('<div class="la-ball-fall"><div></div><div></div><div></div></div>');

				$.ajax({
					url : mindeventsSettings.ajax_url,
					type : 'post',
					data : {
						action : 'rcv_ballot_load_practice_ballot',
						args : {
							post_id : mindeventsSettings.post_id
						}
					},
					success: function(response) {
						ballotContainer.html(response.data.html);
						if(typeof callbackFunction == 'function')
            {
                callbackFunction.call();
            }
					},
					error: function (response) {
						console.log('An error occurred.');
						console.log(response);
					},
				});
			}

		}


		$(document).on('click', '.practice-ballot.live td.option', function (event) {
			event.preventDefault();

			var option = $(this);

			var candidate = option.attr('candidate');
			var rank = option.attr('rank');
			var vote = option.attr('vote');
			option.toggleClass('vote');

			if(vote == 'true') {
				option.attr('vote', 'false');
			} else {
				option.attr('vote', 'true');
			}

		});





		$(document).on('click', '#ballotReset', function (event) {
			var ballotHeight = $('#practiceBallot').height();
			$('#practiceBallot').height(ballotHeight);


			loadBallot(function() {
				var ballot = $('#practiceBallot');
				var ballotOffset = ballot.offset().top - 100;
				$('#ballotFeedback').html('');
				$('#testBallot').prop('disabled',false);;
				$('html, body').animate({
					scrollTop: ballotOffset
				}, 500);
				$('#practiceBallot').height('auto');
			});



		})

		$(document).on('click', '#testBallot', function(event) {
			event.preventDefault();
			var ballotFeedback = $('#ballotFeedback');
			ballotFeedback.html('<div class="la-ball-fall"><div></div><div></div><div></div></div>');
			var casted_votes = [];
			$( 'table.practice-ballot td[vote="true"]' ).each(function( index, e ) {
				var data = {};
				data.rank = $(e).attr('rank');
				data.candidate = $(e).attr('candidate');

        casted_votes.push(data);
			});

			$.ajax({
				url : mindeventsSettings.ajax_url,
				type : 'post',
				data : {
					action : 'rcv_ballot_submit_ballot',
					casted_votes : casted_votes,
				},
				success: function(response) {
					console.log('Success!');
					console.log(response);
					if(response.success == true) {
						ballotFeedback.html(response.data);
					} else {
						ballotFeedback.html(response.data.text);
					}

				},
				error: function (response) {
					console.log('An error occurred.');
					ballotFeedback.html('An error has accurred.');
				},
			});


		})




  });

} ( this, jQuery ));
