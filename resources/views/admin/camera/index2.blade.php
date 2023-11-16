@extends('layouts.admin.default')
@section('content')
@include('includes.admin.breadcrumb')

    <section class="content">
      <div class="container-fluid">
        <div class="row">
        <div class="video-container">
		<div class="container-fluid h-100">
			<div class="row h-100">
				<div class="col-12 h-100">
					<div class="player-overlay">
					<video id="video" width="640" height="480" autoplay></video>
					</div>
				</div>
			</div>
			<div class="controls-section">
				<div class="col-12 col-md-6 col-lg-4 d-flex align-items-center">
					<div class="player-controls">
						<button data-action="up" class="cameraAction icon icon1"><i class="fas fa-play-circle"></i></button>
						<button data-action="left" class="cameraAction icon icon2"><i class="fas fa-play-circle"></i></button>
						<button data-action="home" class="cameraAction icon icon3"><i class="fas fa-home"></i></button>
						<button data-action="right" class="cameraAction icon icon4"><i class="fas fa-play-circle"></i></button>
						<button data-action="down" class="cameraAction icon icon5"><i class="fas fa-play-circle"></i></button>
					</div>
					<div class="btns-controls">
						<button class="cameraZoom btn-primery" data-action="zoomin">Zoom In</button>
						<button class="cameraZoom btn-primery" data-action="zoomout">Zoom Out</button>
						<button class="cameraFocus btn-primery" data-action="focusin">Focus In</button>
						<button class="cameraFocus btn-primery" data-action="focusout">Focus Out</button>
					</div>
				</div>
				<div class="col-12 col-md-6 col-lg-5">
					<div class="dropdowns">
						<div class="dropbar">
							<span>Pan Speed</span>
							<select class="custom-select pan-select">
                                @for($pan=1;$pan<=24;$pan++)
								<option {{$pan==10?'SELECTED':''}} value="{{$pan}}">{{$pan}}</option>
                                @endfor
                            </select>
						</div>
						<div class="dropbar">
							<span>Tilt Speed</span>
							<select class="custom-select  tilt-select">
                                @for($tilt=1;$tilt<=20;$tilt++)
                                <option {{$tilt==10?'SELECTED':''}} value="{{$tilt}}">{{$tilt}}</option>
                                @endfor
							</select>
						</div>
						<div class="dropbar">
							<span>Zoom Speed</span>
							<select class="custom-select  zoom-select">
                            @for($zoom=1;$zoom<=7;$zoom++)
                            <option {{$zoom==5?'SELECTED':''}} value="{{$zoom}}">{{$zoom}}</option>
                            @endfor
							</select>
						</div>
						<div class="dropbar">
							<span>Focus Speed</span>
							<select class="custom-select focus-select">
                                @for($focus=1;$focus<=7;$focus++)
    							<option {{$focus==5?'SELECTED':''}} value="{{$focus}}">{{$focus}}</option>
                                @endfor
                            </select>
						</div>
						<select class="custom-select">
							<option>Auto Focus Unclock</option>
							<option>20</option>
							<option>30</option>
						</select>
					</div>
				</div>
				<div class="col-12 col-md-6 col-lg-3">
					<div class="btns-controls">
						<button class="btn-primery">Set</button>
						<button class="btn-primery">Call</button>
					</div>
					<div class="preset-bar">
						<span>Preset</span>
						<input type="text" class="form-control">
						<span>(0 ~ 254)</span>
					</div>
					<div class="btns-controls">
						<select class="custom-select">
							<option>PTZ</option>
							<option>NDI</option>
						</select>
						<button class="btn-primery">Back</button>
					</div>
				</div>
			</div>
		</div>
	</div>
        </div>
      </div>
    </section>
  </div>
  @include('includes.admin.scripts')
  @include('includes.admin.fullcalander')
  <style>
		html, body { height: 100%; }
		.controls-section {
			left: 0;
			right: 0;
			bottom: 0;
			display: flex;
			padding: 15px;
			position: fixed;
			flex-wrap: wrap;
			overflow: hidden;
  			margin-left: -15px;
			margin-right: -15px;
			align-items: center;
			justify-content: center;
		}
		.player-controls {
			display: grid;
			flex: 0 0 150px;
			max-width: 150px;
			max-height: 150px;
			margin-right: 5px;
			grid-template-columns: repeat(5, 1fr);
			grid-template-rows: repeat(5, 1fr);
		}
		.icon {
			border: 0;
			padding: 0;
			margin: 5px;
			width: 40px;
			height: 40px;
			background: none;
		}
		.icon1 { grid-area: 1 / 2 / 2 / 3; }
		.icon2 { grid-area: 2 / 1 / 3 / 2; }
		.icon3 { grid-area: 2 / 2 / 3 / 3; }
		.icon4 { grid-area: 2 / 3 / 3 / 4; }
		.icon5 { grid-area: 3 / 2 / 4 / 3; }
		.icon1 i { transform: rotate(-90deg); }
		.icon2 i { transform: rotate(-180deg); }
		.icon5 i { transform: rotate(90deg); }
		.icon i { font-size: 40px; }
		.icon3 i { font-size: 35px; }
		.preset-bar {
			margin: 5px 0;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.preset-bar span {
			color: #000;
			font-size: 14px;
			font-weight: 400;
		}
		.preset-bar .form-control {
			margin: 0 10px;
			max-width: 90px;
		}
		.btns-controls {
			width: 100%;
			display: flex;
			flex-wrap: wrap;
		}
		.btns-controls button {
			margin: 5px;
			padding: 5px 5px;
			background: #343a40;
		}
		.btns-controls button, .btns-controls select {
			color: #fff;
			height: 40px;
			line-height: 1;
			font-size: 14px;
			font-weight: 500;
			border-radius: 5px;
			border: 1px solid #343a40;
			flex: 0 0 calc(50% - 10px);
		}
		.btns-controls select {
			margin: 5px;
			color: #000;
			min-height: 40px;
		}
		.custom-select {
			height: 38px;
			padding: 5px 1.75rem 5px .75rem;
		}
		.dropdowns {
			padding: 0;
			display: flex;
			flex-wrap: wrap;
			overflow: hidden;
		}
		.dropbar {
			display: flex;
			flex: 0 0 50%;
			margin: 0 0 10px;
			align-items: center;
			justify-content: flex-end;
		}
		.dropbar select {
			width: 80px;
			flex: 0 0 80px;
			margin: 0 0 0 10px;
		}
		select {
			width: 100%;
			color: #000;
			line-height: 1;
			font-size: 16px;
			font-weight: 500;
		}
		.video-container {
			display: flex;
			padding: 15px 0;
			overflow: hidden;
			background: #f4f6f9;
			align-items: center;
			margin-bottom: 180px;
			height: calc(100% - 180px);
		}
		.player-overlay {
			width: 100%;
			height: 100%;
			position: relative;
		}
		.player-overlay:after {
			content:'';
    		display: block;
			padding-bottom: 52.25%;
		}
		video {
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			position: absolute;
		}
		@media screen and (max-width: 991.98px) {
			.video-container {
				margin-bottom: 355px;
				height: calc(100% - 355px);
			}
			.btns-controls { margin: 10px 0; }
		}
		@media screen and (max-width: 767.98px) {
			.video-container {
				margin-bottom: 468px;
				height: calc(100% - 468px);
			}
			.dropdowns { margin: 10px 0; }
			.btns-controls { margin: 0; }
			.btns-controls button, .btns-controls select { height: auto; }
		}
	</style>

<script>
		// Put event listeners into place
		window.addEventListener("DOMContentLoaded", function() {
			// Grab elements, create settings, etc.
			//var canvas = document.getElementById('canvas');
			//var context = canvas.getContext('2d');
			var video = document.getElementById('video');
			var mediaConfig = { video: true };
			var errBack = function(e) {
				console.log('An error has occurred!', e)
			};

			// Put video listeners into place
			if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
				navigator.mediaDevices.getUserMedia(mediaConfig).then(function(stream) {
					//video.src = window.URL.createObjectURL(stream);
					video.srcObject = stream;
					video.play();
				});
			}

			/* Legacy code below! */
			else if (navigator.getUserMedia) { // Standard
				navigator.getUserMedia(mediaConfig, function(stream) {
					video.src = stream;
					video.play();
				}, errBack);
			} else if (navigator.webkitGetUserMedia) { // WebKit-prefixed
				navigator.webkitGetUserMedia(mediaConfig, function(stream) {
					video.src = window.webkitURL.createObjectURL(stream);
					video.play();
				}, errBack);
			} else if (navigator.mozGetUserMedia) { // Mozilla-prefixed
				navigator.mozGetUserMedia(mediaConfig, function(stream) {
					video.src = window.URL.createObjectURL(stream);
					video.play();
				}, errBack);
			}

			// Trigger photo take
			// document.getElementById('snap').addEventListener('click', function() {
			// 	context.drawImage(video, 0, 0, 640, 480);
			// });
		}, false);
	</script>

<script>
$(document).ready(function () {
$('body').addClass('sidebar-collapse');
   
    $('.cameraAction').mousedown(function(){
        let action = $(this).attr('data-action')+'&'+$('.pan-select').val()+'&'+$('.tilt-select').val()
        cameraAction(action);
    })
    $('.cameraZoom').mousedown(function(){
        let action = $(this).attr('data-action')+'&'+$('.zoom-select').val();
        cameraAction(action);
    })
    $('.cameraFocus').mousedown(function(){
        let action = $(this).attr('data-action')+'&'+$('.focus-select').val();
        cameraAction(action);
    })

    $('.cameraAction').mouseup(function(){
        cameraAction('ptzstop')
    })
    $('.cameraZoom').mouseup(function(){
        cameraAction('zoomstop')
    })
    $('.cameraFocus').mouseup(function(){
        cameraAction('focusstop')
    })
    
}); //$(document).ready(function ()
 
function cameraAction(action){
    // http://[camera ip]/cgi-bin/ptzctrl.cgi?ptzcmd&[action]&[pan speed]&[tilt speed]
    var APIURL = 'http://192.168.100.88/cgi-bin/ptzctrl.cgi?ptzcmd&';
    $.get(APIURL+action, {}, function(){
        
    })
}
function displayMessage(message) {
    toastr.success(message, 'Event');
} 

function displayError(message) {
    toastr.error(message, 'Error!');
} 
  
</script>
  
@stop
