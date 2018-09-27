@extends('../master')

@section('styles')
	{{ HTML::style('assets/cpanel/css/explorer.css') }}
	{{ HTML::style('assets/cpanel/css/jquery.snippet.min.css') }}
@endsection

@section('scriptIncludes')
	{{ HTML::script('assets/cpanel/js/jquery.snippet.min.js') }}
	{{ HTML::script('assets/cpanel/js/actions.js') }}
@endsection

@section('body')
	<div class="container">
		<div class="explorer-main">			
			<table id="files">
			<thead>
			<tr><td colspan="3">Path : /</td></tr>
			<tr><td width="30px">Type</td><td>Name</td><td>Size</td></tr>
			</thead>
				@foreach($files as $file)
					@if($file['type']==='DIR')
						<tr><td><i class="fa fa-folder"></i></td><td><label data-file="{{ $file['name'] }}">{{ $file['name'] }}</label></td><td></td></tr>
					@else				
						<tr><td><i class="fa fa-file"></i></td><td><label data-file="{{ $file['name'] }}">{{ $file['name'] }}</label></td><td>{{ $file['size'] }}</td></tr>
					@endif
				@endforeach
			</table>
		</div>
	</div>

	<div id="fileViewerModal" class="reveal-modal" style="display:none">
	<div id="toolbar">
	<input type="hidden" id="fileName">
	File : <span id="spanfileName"></span>
	<a class="anchorBtn" onClick="save()" style="float:right">Save</a>
	<a class="anchorBtn" id="closeModalBtn" onClick="cancel()" style="float:right">Cancel</a>
	</div>
	<textarea id="fileContent" style="height:500px;width:1000px;top:100px;overflow:scroll-y" spellcheck="false">
	</textarea>
	</div>
@endsection

@section('script')
	$('#files label').click(function(){
		fileName=$(this).attr('data-file');
		$.ajax({
			method:'POST',
			url:'cpanel/ajax/getFile',
			data:'fileName='+fileName,
			success:function(reply){
				console.log(reply);
				$("#fileContent").text("");
				$("#fileContent").text(reply);
				$("#spanfileName").text(fileName);
				$("#fileName").val(fileName);
				$('#fileViewerModal').trigger('openModal');
			}
		});
	});
	window.onload=function(){
		$(document).ready(function(){					
			$('#fileViewerModal').easyModal();
			$('#manageBtn').on('click',function(){
				$('#fileViewerModal').trigger('openModal');
			});
			$('#closeModalBtn').on('click',function(){
				$('#fileViewerModal').trigger('closeModal');
			});
			//$("#fileContent").snippet("php",{style:"navy",showNum:true});
		});
	};
@endsection