<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Laravel</title>
		<!-- Fonts -->
		<link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
		<link href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<!-- Styles -->
		<style>
			html, body {
			background-color: #fff;
			color: #636b6f;
			font-family: 'Nunito', sans-serif;
			font-weight: 200;
			height: 100vh;
			margin: 0;
			}
			.full-height {
			height: 100vh;
			}
			.flex-center {
			align-items: center;
			display: flex;
			justify-content: center;
			}
			.position-ref {
			position: relative;
			}
			.top-right {
			position: absolute;
			right: 10px;
			top: 18px;
			}
			.content {
			text-align: center;
			}
			.title {
			font-size: 84px;
			}
			.links > a {
			color: #636b6f;
			padding: 0 25px;
			font-size: 13px;
			font-weight: 600;
			letter-spacing: .1rem;
			text-decoration: none;
			text-transform: uppercase;
			}
			.m-b-md {
			margin-bottom: 30px;
			}
		</style>
	</head>
	<body>
        <div class="modal fade" id="GameModal" tabindex="-1" role="dialog" aria-labelledby="GameModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{route('game/create')}}" id="addForm" name="addForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="GameModalLabel">Tambah bertandingan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <div class="form-group row">
                                <label for="mdate" class="col-md-4 col-form-label text-md-left">Tanggal pertandingan</label>
                                <div class="col-md-8">
                                    <input id="mdate" type="date" class="form-control" name="mdate">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="stadium" class="col-md-4 col-form-label text-md-left">Stadium</label>
                                <div class="col-md-8">
                                    <textarea id="stadium" type="text" class="form-control" name="stadium"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="team1" class="col-md-4 col-form-label text-md-left">Team 1</label>
                                <div class="col-md-8">
                                    <textarea id="team1" type="text" class="form-control" name="team1"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="team2" class="col-md-4 col-form-label text-md-left">Team 2</label>
                                <div class="col-md-8">
                                    <textarea id="team2" type="text" class="form-control" name="team2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-modal-dismiss" data-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-primary with-loading" id="addBtn" name="addBtn">
                            Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
		<div class="container my-5">
			<div class="card">
                <div class="card-header">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#GameModal">Tambah data</button>
                </div>
				<div class="card-body">
					<table id="GameTable" class="display">
						<thead>
							<tr>
								<th>Id</th>
								<th>Tanggal pertandingan</th>
								<th>Stadium</th>
								<th>Team 1</th>
								<th>Team 2</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript">
			$(document).ready( function () {
                var request = {
                    "start": 0,
                    "length": 10
                };
			    let gameDt = $('#GameTable').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "lengthMenu": [
                        [10, 15, 25, 50, -1],
                        [10, 15, 25, 50, "All"]
                    ],
                    "ajax": {
                        "url": "{{route('game/select')}}",
                        "type": "POST",
                        "headers": {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
                        },
                        "beforeSend": function(xhr) {
                            xhr.setRequestHeader("Authorization", "Bearer " + $('#secret').val());
                        },
                        "Content-Type": "application/json",
                        "data": function(data) {
                            request.draw = data.draw;
                            request.start = data.start;
                            request.length = data.length;
                            console.log(data)
                            request.searchkey = data.search.value || "";
                            return (request);
                        },
                    },
                    "columns": [
                        {
                            "data": "id"
                        },
                        {
                            "data": "mdate"
                        },
                        {
                            "data": "stadium"
                        },
                        {
                            "data": "team1"
                        },
                        {
                            "data": "team2"
                        },
                    ]
                });
                
                $('#GameModal').on('hidden.bs.modal', function (e) {
                    gameDt.draw();
                })
                $('#addBtn').click(function(){
                    let request = $('#addForm').serialize();
                    var url = $("#addForm").attr('action');
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: request,
                        success: function(data) {
                            alert(data.message)
                        },
                        error: function(data) {
                            alert("Terjadi kesalahan")
                        }
                    });
                })
			} );
		</script>
	</body>
</html>