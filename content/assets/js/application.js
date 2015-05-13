
						$('.learnmore').click(
						function(e)
						{   e.preventDefault();
							$('div.modal').slideDown();
						}
						)
						$('.close').click(
						function()
						{
							console.log('close');
							
						 $('div.modal').slideUp();
							
						}
						);
						
						function settargetdateinminute() {
							var set_date = new Date($("#countdownTime").text());
							var today = new Date();
							difference = (set_date.valueOf()) - ( today.valueOf());
							return Math.round(difference / 60000);
						}

						if ($("#first_countdown-camp").length) {
							var remain_mins = settargetdateinminute();
							var myCountdown2 = new Countdown({
								time: remain_mins * 60,
								width: 100,
								height: 40,
								rangeHi: "day",
								rangeLo: "minute",
								target: "first_countdown-camp",
								padding: 0.7,
								labelText: {
									second: "SEC.",
									minute: "MIN.",
									hour: "HOURS",
									day: "DAYS",
									month: "MONTHS",
									year: "YEARS"
								},
								labels: {
									font: "Arial",
									color: "#838383",
									weight: "normal",
									offset: 5,  // Number of pixels to push the labels down away from numbers.
									textScale: 1.5
								}
							});
						}