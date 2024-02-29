var fnRemoveFileFromFileList;
var fnAppendImgBlock;
var sDomImageBlock;
var sDomVideoBlock;
var $File0Global;
$(document).ready(function()
{
	$('#JqFile').on('change' , function(event)
	{
		$('#JqPreviewDiv').html('');
		let nFileCount = event.target.files.length;
		for (let i=0;i<nFileCount;i++) {
			let oFile = event.target.files[i];
			if (!oFile.type) {
				$('#JqFileError').text('瀏覽器不支持預覽');
				return;
			}
			// if (!oFile.type.match('image.*')) {
			// 	$('#JqFileError').text('檔案可能不是圖片');
			// 	return;
			// }
		}

		if (window.FileList && window.File && window.FileReader) {
			$('#JqFileError').text('');
			// $('.JqFile')[0].files === event.target.files
			fnAppendImgBlock(event.target);			
		}
	});

	// 主要目標是.JqDeleteImg  限定PreviewDiv內的.JqDeleteImg 才能跟著監聽新增的DOM
	$('#JqPreviewDiv').on('click' ,'.JqDeleteImg', function(event)
	{
		let nIndex = $(this).attr('data-index');
		console.log(nIndex);
		fnRemoveFileFromFileList(parseInt(nIndex),$('#JqFile')[0]);
		$('#JqPreviewDiv').html('');
		fnAppendImgBlock($('#JqFile')[0]);
	});

	$('.JqResetFile').on('click' , function(event)
	{
		$('#JqPreviewDiv').html('');
		$('#JqFile')[0].value='';
	});

	$('select[name="nKid"]').on('change' , function(event)
	{
		let nType0 = $('option:selected', this).attr('date-nType0');
		if(nType0 === '1')
		{
			$('.JqStockCol').removeClass('active');
		}
		else
		{
			$('.JqStockCol').addClass('active');
		}
	});
});

fnRemoveFileFromFileList = function(nIndex,$Input0) {
	const dt = new DataTransfer();
	const { files } = $Input0;
	for (let i = 0; i < files.length; i++) {
		const file = files[i];
		if (nIndex !== i) dt.items.add(file) // here you exclude the file. thus removing it.
		$Input0.files = dt.files;
	}
}

fnAppendImgBlock = function($File0){
	let nFileCount = $File0.files.length;
	for (let i=0;i<nFileCount;i++) {
		let oFile = $File0.files[i];
		const oReader = new FileReader();
		if(oFile.type === 'image/gif'){
			let sAppendBlock = sDomImageBlock;
			sAppendBlock = sAppendBlock.replace(/:index:/g,i.toString());
			oReader.addEventListener('load', event => {
				sAppendBlock = sAppendBlock.replace(/:src:/g,event.target.result);
				sAppendBlock = sAppendBlock.replace(/:title:/g,oFile.name);
				$('#JqPreviewDiv').append(sAppendBlock);
			});
			oReader.readAsDataURL(oFile);

		}
		else if(oFile.type === 'video/mp4'){
			let sAppendBlock = sDomVideoBlock;
			sAppendBlock = sAppendBlock.replace(/:index:/g,i.toString());
			oReader.addEventListener('load', event => {
				sAppendBlock = sAppendBlock.replace(/:src:/g,event.target.result);
				sAppendBlock = sAppendBlock.replace(/:title:/g,oFile.name);
				$('#JqPreviewDiv').append(sAppendBlock);
			});
			oReader.readAsDataURL(oFile);
		}
	}
}

sDomImageBlock = 
'<div>'+
	'<a class="BtnAny2 JqDeleteImg" href="javascript:void(0)" data-index=":index:">'+
		'<i class="fas fa-times"></i>'+
	'</a>'+
	'<div class="BlockImg MarginTop5">'+
		'<img src=":src:" alt="" height="300" title=":title:">'+
	'</div>'+
	'<div class="Block MarginBottom20">'+
		'<span class="InlineBlockTit">'+'檔名'+'</span>'+
		'<span>:title:</span>'+
	'</div>'+
'</div>';
// '<div class="Block MarginBottom20">'+
// '<span class="InlineBlockTit">'+'描述'+'</span>'+
// '<div class="Ipt">'+
// 	'<input type="text" name="aDescription[:index:]" value="">'+
// '</div>'+
// '</div>'+
sDomVideoBlock = 
'<div>'+
	'<a class="BtnAny2 JqDeleteImg" href="javascript:void(0)" data-index=":index:">'+
		'<i class="fas fa-times"></i>'+
	'</a>'+
	'<video controls>'+
		'<source src=":src:" type="video/mp4" height="300" title=":title:">'+
	'</video>'+	
'</div>';
// '<div class="Block MarginBottom20">'+
// 	'<span class="InlineBlockTit">'+'檔名'+'</span>'+
// 	'<span>:title:</span>'+
// '</div>'+
// '<div class="Block MarginBottom20">'+
// '<span class="InlineBlockTit">'+'價錢'+'</span>'+
// '<div class="Ipt">'+
// 	'<input type="text" name="aPrice0[:index:]" value="">'+
// '</div>'+
// '</div>'+