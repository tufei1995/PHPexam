function ajaxRequest(requestUrl, page, keyword, successFunction, colspan){
	$.AMUI.progress.start();
	if (typeof(colspan) == 'undefined') colspan=10;
	$('#body').html('<tr><td colspan="'+colspan+'"><span class="am-icon-spinner am-icon-pulse"></span> 加载中...</td></tr>');
	if(typeof(page)=='undefined')page=1;
	$.ajax({
		url : requestUrl,
		data : {
			'page':page,
			'keyword':keyword
		},
		type : 'post',
		dataType : 'json',
		success:function(data) {
			if(data.status =='1'){
				str = data.data.length == 0 ? '<tr><td colspan="'+colspan+'"><span class="am-icon-warning"></span> 暂无数据</td></tr>' : '';
				successFunction(data);
				$.AMUI.progress.done();
			} else {
				showFail(data.msg);
				$.AMUI.progress.done();
			}
		}
	});
}
function infoRequest(requestUrl, id, successFunction){
	$.AMUI.progress.start();
	$.ajax({
		url:requestUrl,
		data:{
			'id':id
		},
		type:'post',
		dataType:'json',
		success:function(data) {
			if(data.status =='1'){
				successFunction(data);
				$.AMUI.progress.done();
			} else {
				showFail(data.msg);
				$.AMUI.progress.done();
			}
		}
	});
}
function access(page, keyword){
	if(typeof(page) == 'undefined') page = 1;
	ajaxRequest('', page, keyword, function(data){
		var i = 0;
		$.each(data.data, function(n, value){
			i++;
			value['show'] = value['is_show'] == '0' ? '<label><input type="radio" class="show" name="'+value['aid']+'" value="1"> 是</label> <label><input type="radio" class="show" name="'+value['aid']+'" value="0" checked> 否</label>' : '<label><input type="radio" class="show" name="'+value['aid']+'" value="1" checked> 是</label> <label><input type="radio" class="show" name="'+value['aid']+'" value="0"> 否</label>';
			value['struct'] = value['pid'] != '0' ? value['struct'] : '<a href="javascript:fold('+value['aid']+');" title="点击可折叠">'+value['struct']+'</a>';
			add = value['pid'] != '0' ? '' : ' <a href="javascript:$(\'#add-modal #pid\').val('+value['aid']+');" class="am-text-success" data-am-modal="{target: \'#add-modal\', closeViaDimmer: 1}"><span class="am-icon-plus">新增</a>';
			if(typeof(value['struct']) == 'undefined') value['struct'] = value['title'];
			str += '<tr id="'+value['aid']+'" class="p'+value['pid']+'">';
			str += '<td><input type="checkbox" class="check" name="ids" value="'+value['aid']+'"></td><td class="am-hide-sm-only">'+i+'</td><td style="text-align: left;">'+value['struct']+'</td><td>'+value['key']+'</td><td>'+value['title']+'</td><td>'+value['aid']+'</td><td><span class="am-icon-'+value['icon']+'"></span></td><td>'+value['show']+'</td>';
			str += '<td><div class="am-btn-group am-btn-group-xs"><a href="javascript:accessInfo('+value['aid']+');" data-am-modal="{target: \'#edit-modal\', closeViaDimmer: 1}"><span class="am-icon-pencil">编辑</a> <a href="javascript:deletekey(\'access\', '+value['aid']+');" class="am-text-danger"><span class="am-icon-trash">删除</a>'+add+'</div></td></tr>';
		});
		$('#body').html(str);
	});
}
function accessInfo(id){
	infoRequest('', id, function(data){
		$.each(data.data, function(k, v){
			$('#edit #'+k).val(v);
		});
	});
}
function user(page, keyword){
	if(typeof(page) == 'undefined') page = 1;
	ajaxRequest('', page, keyword, function(data){
		$.each(data.data, function(n, value){
			str += '<tr id="'+value['uid']+'">';
			str += '<td><input type="checkbox" class="check" name="ids" value="'+value['uid']+'"></td><td class="am-hide-sm-only">'+value['uid']+'</td><td>'+value['name']+'</td><td>'+value['username']+'</td><td>'+value['class']+'</td><td>'+value['group']+'</td><td class="am-hide-sm-only">'+value['create_time']+'</td>';
			str += '<td><div class="am-btn-group am-btn-group-xs"><a href="javascript:userInfo('+value['uid']+');" data-am-modal="{target: \'#edit-modal\', closeViaDimmer: 1}"><span class="am-icon-pencil">编辑</a> <a href="../setting/grant?u='+value['uid']+'" class="am-text-warning"><span class="am-icon-wrench">授权</a> <a href="javascript:deletekey(\'user\', '+value['uid']+');" class="am-text-danger"><span class="am-icon-trash">删除</a></div></td></tr>';
		});
		$('.am-pagination').html(pagenav(data.total, page, keyword, 'user'));
		$('#body').html(str);
	}, 8);
}
function verify(page, keyword){
	if(typeof(page) == 'undefined') page = 1;
	ajaxRequest('', page, keyword, function(data){
		$.each(data.data, function(n, value){
			str += '<tr id="'+value['uid']+'">';
			str += '<td><input type="checkbox" class="check" name="ids" value="'+value['uid']+'"></td><td class="am-hide-sm-only">'+value['uid']+'</td><td>'+value['name']+'</td><td>'+value['username']+'</td><td>'+value['group']+'</td><td class="am-hide-sm-only">'+value['create_time']+'</td>';
			str += '<td><div class="am-btn-group am-btn-group-xs"><a href="javascript:verifyOk('+value['uid']+');"><span class="am-icon-check">通过</a> <a href="javascript:deletekey(\'user\', '+value['uid']+');" class="am-text-danger"><span class="am-icon-trash">删除</a></div></td></tr>';
		});
		$('.am-pagination').html(pagenav(data.total, page, keyword, 'verify'));
		$('#body').html(str);
	}, 7);
}
function studentlist(page, keyword){
	if(typeof(page) == 'undefined') page = 1;
	ajaxRequest('', page, keyword, function(data){
		$.each(data.data, function(n, value){
			str += '<tr id="'+value['uid']+'">';
			str += '<td><input type="checkbox" class="check" name="ids" value="'+value['uid']+'"></td><td class="am-hide-sm-only">'+value['uid']+'</td><td>'+value['name']+'</td><td>'+value['username']+'</td><td>'+value['class']+'</td>';
			str += '<td><div class="am-btn-group am-btn-group-xs"><a href="javascript:deletekey(\'report\', \''+value['uid']+'-'+getUrlParam('id')+'\');" class="am-text-danger"><span class="am-icon-trash">删除</a></div></td></tr>';
		});
		$('#body').html(str);
	});
}
function addstudent(page, keyword){
	if(typeof(page) == 'undefined') page = 1;
	ajaxRequest('', page, keyword, function(data){
		$.each(data.data, function(n, value){
			str += '<tr id="'+value['uid']+'">';
			str += '<td><input type="checkbox" class="check" name="ids" value="'+value['uid']+'"></td><td class="am-hide-sm-only">'+value['uid']+'</td><td>'+value['name']+'</td><td>'+value['username']+'</td><td>'+value['class']+'</td>';
			str += '<td><div class="am-btn-group am-btn-group-xs"><a href="javascript:adduser('+value['uid']+');" class="am-text-primary"><span class="am-icon-plus"> 添加</a></div></td></tr>';
		});
		$('#body').html(str);
	});
}
function adduser(uid){
	infoRequest('', uid, function(){
		showSuccess('添加成功！');
	});
}
function userInfo(id){
	infoRequest('', id, function(data){
		$.each(data.data, function(k, v){
			$('#edit #'+k).val(v);
		});
	});
}
function group(page, keyword){
	if(typeof(page) == 'undefined') page = 1;
	ajaxRequest('', page, keyword, function(data){
		$.each(data.data, function(n, value){
			str += '<tr id="'+value['gid']+'">';
			str += '<td><input type="checkbox" class="check" name="ids" value="'+value['gid']+'"></td><td class="am-hide-sm-only">'+value['gid']+'</td><td>'+value['name']+'</td><td class="am-hide-sm-only">'+value['create_time']+'</td>';
			str += '<td><div class="am-btn-group am-btn-group-xs"><a href="javascript:groupInfo('+value['gid']+');" data-am-modal="{target: \'#edit-modal\', closeViaDimmer: 1}"><span class="am-icon-pencil">编辑</a> <a href="../setting/grant?g='+value['gid']+'" class="am-text-warning"><span class="am-icon-wrench">授权</a> <a href="javascript:deletekey(\'group\', '+value['gid']+');" class="am-text-danger"><span class="am-icon-trash">删除</a></div></td></tr>';
		});
		$('.am-pagination').html(pagenav(data.total, page, keyword, 'group'));
		$('#body').html(str);
	});
}
function groupInfo(id){
	$("input[name='access[]']").prop("checked", false);
	infoRequest('', id, function(data){
		$.each(data.data, function(k, v){
			$('#edit #'+k).val(v);
			if (k == 'access') {
				arr = v.split(',');
				$.each(arr, function(n, value){
					$("#edit input:checkbox[name='access[]'][value='"+value+"']").prop("checked",true);
				});
			}
		});
	});
}
function testlist(page, keyword){
	if(typeof(page) == 'undefined') page = 1;
	ajaxRequest('', page, keyword, function(data){
		$.each(data.data, function(n, value){
			str += '<tr id="'+value['tid']+'">';
			str += '<td><input type="checkbox" class="check" name="ids" value="'+value['tid']+'"></td><td class="am-hide-sm-only">'+value['tid']+'</td><td>'+value['title']+'</td><td>'+value['uid']+'</td><td>'+value['start']+'</td><td class="am-hide-sm-only">'+value['end']+'</td>';
			str += '<td><div class="am-btn-group am-btn-group-xs"><a href="./statistic?id='+value['tid']+'" class="am-text-primary"><span class="am-icon-file-text">分析</a> <a href="./export?id='+value['tid']+'" class="am-text-primary"><span class="am-icon-download">导出</a> <a href="./student_list?id='+value['tid']+'" class="am-text-warning"><span class="am-icon-user">考生名单</a> <a href="report?id='+value['tid']+'" class="am-text-success"><span class="am-icon-eye">成绩单</a> <a href="javascript:deletekey(\'test\', '+value['tid']+');" class="am-text-danger"><span class="am-icon-trash">删除</a> </div></td></tr>';
		});
		$('.am-pagination').html(pagenav(data.total, page, keyword, 'testlist'));
		$('#body').html(str);
	}, 7);
}
function mark(page, keyword){
	if(typeof(page) == 'undefined') page = 1;
	ajaxRequest('', page, keyword, function(data){
		$.each(data.data, function(n, value){
			value['process'] = value['need'] == '0' ? '100' : value['done']/value['need']*100;
			str += '<tr id="'+value['tid']+'">';
			str += '<td><input type="checkbox" class="check" name="ids" value="'+value['tid']+'"></td><td class="am-hide-sm-only">'+value['tid']+'</td><td>'+value['title']+'</td><td>'+value['uid']+'</td><td>'+value['start']+'</td><td class="am-hide-sm-only">'+value['end']+'</td><td>'+value['process']+'%</td>';
			str += '<td><div class="am-btn-group am-btn-group-xs"><a href="?id='+value['tid']+'"><span class="am-icon-pencil"> 开始阅卷</a></td></tr>';
		});
		$('.am-pagination').html(pagenav(data.total, page, keyword, 'mark'));
		$('#body').html(str);
	}, 9);
}
function chapter(page, keyword){
	if(typeof(page) == 'undefined') page = 1;
	ajaxRequest('', page, keyword, function(data){
		$.each(data.data, function(n, value){
			str += '<tr id="'+value['id']+'">';
			str += '<td><input type="checkbox" class="check" name="ids" value="'+value['id']+'"></td><td class="am-hide-sm-only">'+value['id']+'</td><td style="text-align: left;">'+value['struct']+'</td><td class="am-hide-sm-only">'+value['uid']+'</td><td>'+value['title']+'</td>';
			str += '<td><div class="am-btn-group am-btn-group-xs"><a href="javascript:chapterInfo('+value['id']+');" data-am-modal="{target: \'#edit-modal\', closeViaDimmer: 1}"><span class="am-icon-pencil">编辑</a> <a href="javascript:deletekey(\'chapter\', '+value['id']+');" class="am-text-danger"><span class="am-icon-trash">删除</a></div></td></tr>';
		});
		$('.am-pagination').html(pagenav(data.total, page, keyword, 'chapter'));
		$('#body').html(str);
	});
}
function chapterInfo(id){
	infoRequest('../common/getChapterInfoById', id, function(data){
		$('.id').val(data.data.id);
		$('.name').val(data.data.title);
		$('#edit-modal .courseid').val(data.data.courseid);
		infoRequest('../common/getChapterInfo', data.data.courseid, function(cdata){
			$("#edit-modal .pid").html('<option value="">请选择章节</option><option value="0">作为新的一章</option>'+cdata.data);
			$(".pid").val(data.data.pid);			
		});
	});
}
function course(page, keyword){
	if(typeof(page) == 'undefined') page = 1;
	ajaxRequest('', page, keyword, function(data){
		$.each(data.data, function(n, value){
			str += '<tr id="'+value['id']+'">';
			str += '<td><input type="checkbox" class="check" name="ids" value="'+value['id']+'"></td><td class="am-hide-sm-only">'+value['id']+'</td><td class="am-hide-sm-only">'+value['uid']+'</td><td>'+value['title']+'</td>';
			str += '<td><div class="am-btn-group am-btn-group-xs"><a href="javascript:courseInfo('+value['id']+');" data-am-modal="{target: \'#edit-modal\', closeViaDimmer: 1}"><span class="am-icon-pencil">编辑</a> <a href="javascript:deletekey(\'course\', '+value['id']+');" class="am-text-danger"><span class="am-icon-trash">删除</a></div></td></tr>';
		});
		$('.am-pagination').html(pagenav(data.total, page, keyword, 'course'));
		$('#body').html(str);
	});
}
function courseInfo(id){
	infoRequest('', id, function(data){
		$("#cover").attr('src', data.data.cover);
		$.each(data.data, function(key, value){
			$('#edit input[name="'+key+'"]').val(value);
		});
	});
}
function choice(page, keyword){
	if(typeof(page) == 'undefined') page = 1;
	ajaxRequest('', page, keyword, function(data){
		$.each(data.data, function(n, value){
			value['type'] = value['type'] == 'single' ? '单选' : '多选';
			str += '<tr id="'+value['cid']+'">';
			str += '<td><input type="checkbox" class="check" name="ids" value="'+value['cid']+'"></td><td class="am-hide-sm-only">'+value['cid']+'</td><td>'+value['type']+'</td><td class="am-table-title">'+value['title']+'</td><td>'+value['right_ans']+'</td><td>'+value['difficulty']+'</td><td  class="table-id am-hide-sm-only">'+value['pid']+'</td>';
			str += '<td><div class="am-btn-group am-btn-group-xs"><a href="javascript:choiceInfo('+value['cid']+');" data-am-modal="{target: \'#edit-modal\', closeViaDimmer: 1}"><span class="am-icon-pencil">编辑</a> <a href="javascript:deletekey(\'choice\', '+value['cid']+');" class="am-text-danger"><span class="am-icon-trash">删除</a></div></td></tr>';
		});
		$('.am-pagination').html(pagenav(data.total, page, keyword, 'choice'));
		$('#body').html(str);
	}, 8);
}

function collect(id, type, t){
	$.AMUI.progress.start();
	$.ajax({
		url:'../exam/report',
		data:{
			'qid':id,
			'type':type,
			't':t,
		},
		type:'post',
		dataType:'json',
		success:function(data) {
			if(data.status =='1'){
				s = type == 'choice' ? '#cstar' : '#jstar';
				if (t == '1') {
					$(s+'a'+id).attr('href', 'javascript:collect('+id+', \''+type+'\', 0)');
					$(s+id).removeClass('am-icon-star');
					$(s+id).addClass('am-icon-star-o');
				} else {
					$(s+'a'+id).attr('href', 'javascript:collect('+id+', \''+type+'\', 1)');
					$(s+id).removeClass('am-icon-star-o');
					$(s+id).addClass('am-icon-star');
				}
				$.AMUI.progress.done();
			} else {
				showFail(data.msg);
				$.AMUI.progress.done();
			}
		} 
	});
}

function choiceInfo(id){
	infoRequest('', id, function(data){
		infoRequest('../common/getSectionInfo', data.data.course, function(cdata){
			$('.course').val(data.data.course);
			$('.pid').html('<option value="">请选择章节</option>'+cdata.data);
			$('.pid').val(data.data.pid);
		});
		$.each(data.data, function(n, value){
			if (n == 'type') {
				if (value == 'single') {
					$("#edit input:radio[name='"+n+"'][value='single']").prop("checked", true);
					$("#edit .multiple").hide();
					$("#edit .single").show();
				} else  {
					$("#edit input:radio[name='"+n+"'][value='multiple']").prop("checked", true);
					$("#edit .multiple").show();
					$("#edit .single").hide();
				}
			}
			if (n == 'right_ans') {
				if (value.length == 1) {
					$("#edit input:radio[name='right_ans'][value='"+value+"']").prop("checked", true);
				} else {
					answers = value.split('，');
					$.each(answers, function(k, v){
						$("#edit input:checkbox[name='right_ans[]'][value='"+v+"']").prop("checked", true);
					});
				}
			}
			else $("#edit #"+n).val(value);
		});
	});
}
function judge(page, keyword){
	if(typeof(page) == 'undefined') page = 1;
	ajaxRequest('', page, keyword, function(data){
		$.each(data.data, function(n, value){
			str += '<tr id="'+value['jid']+'">';
			str += '<td><input type="checkbox" class="check" name="ids" value="'+value['jid']+'"></td><td class="am-hide-sm-only">'+value['jid']+'</td><td class="am-table-title">'+value['title']+'</td><td>'+value['right_ans']+'</td><td>'+value['difficulty']+'</td><td  class="table-id am-hide-sm-only">'+value['pid']+'</td>';
			str += '<td><div class="am-btn-group am-btn-group-xs"><a href="javascript:judgeInfo('+value['jid']+');" data-am-modal="{target: \'#edit-modal\', closeViaDimmer: 1}"><span class="am-icon-pencil">编辑</a> <a href="javascript:deletekey(\'judge\', '+value['jid']+');" class="am-text-danger"><span class="am-icon-trash">删除</a></div></td></tr>';
		});
		$('.am-pagination').html(pagenav(data.total, page, keyword, 'judge'));
		$('#body').html(str);
	}, 8);
}
function judgeInfo(id){
	infoRequest('', id, function(data){
		infoRequest('../common/getSectionInfo', data.data.course, function(cdata){
			$('.course').val(data.data.course);
			$('.pid').html('<option value="">请选择章节</option>'+cdata.data);
			$('.pid').val(data.data.pid);
		});
		$.each(data.data, function(n, value){
			if (n == 'right_ans') {
				$("#edit input:radio[name='right_ans'][value='"+value+"']").prop("checked", true);
			}
			$("#edit #"+n).val(value);
		});
	});
}
function subjective(page, keyword){
	if(typeof(page) == 'undefined') page = 1;
	ajaxRequest('', page, keyword, function(data){
		$.each(data.data, function(n, value){
			str += '<tr id="'+value['sid']+'">';
			str += '<td><input type="checkbox" class="check" name="ids" value="'+value['sid']+'"></td><td class="am-hide-sm-only">'+value['sid']+'</td><td class="am-table-title">'+value['title']+'</td><td>'+value['difficulty']+'</td><td  class="table-id am-hide-sm-only">'+value['pid']+'</td>';
			str += '<td><div class="am-btn-group am-btn-group-xs"><a href="javascript:subjectiveInfo('+value['sid']+');" data-am-modal="{target: \'#edit-modal\', closeViaDimmer: 1}"><span class="am-icon-pencil">编辑</a> <a href="javascript:deletekey(\'subjective\', '+value['sid']+');" class="am-text-danger"><span class="am-icon-trash">删除</a></div></td></tr>';
		});
		$('.am-pagination').html(pagenav(data.total, page, keyword, 'subjective'));
		$('#body').html(str);
	}, 8);
}
function subjectiveInfo(id){
	infoRequest('', id, function(data){
		infoRequest('../common/getSectionInfo', data.data.course, function(cdata){
			$('.course').val(data.data.course);
			$('.pid').html('<option value="">请选择章节</option>'+cdata.data);
			$('.pid').val(data.data.pid);
		});
		$.each(data.data, function(n, value){
			$("#edit #"+n).val(value);
		});
	});
}
function filling(page, keyword){
	if(typeof(page) == 'undefined') page = 1;
	ajaxRequest('', page, keyword, function(data){
		$.each(data.data, function(n, value){
			str += '<tr id="'+value['fid']+'">';
			str += '<td><input type="checkbox" class="check" name="ids" value="'+value['fid']+'"></td><td class="am-hide-sm-only">'+value['fid']+'</td><td class="am-table-title">'+value['title']+'</td><td>'+value['difficulty']+'</td><td  class="table-id am-hide-sm-only">'+value['pid']+'</td>';
			str += '<td><div class="am-btn-group am-btn-group-xs"><a href="javascript:fillingInfo('+value['fid']+');" data-am-modal="{target: \'#edit-modal\', closeViaDimmer: 1}"><span class="am-icon-pencil">编辑</a> <a href="javascript:deletekey(\'filling\', '+value['fid']+');" class="am-text-danger"><span class="am-icon-trash">删除</a></div></td></tr>';
		});
		$('.am-pagination').html(pagenav(data.total, page, keyword, 'filling'));
		$('#body').html(str);
	}, 8);
}
function fillingInfo(id){
	infoRequest('', id, function(data){
		infoRequest('../common/getSectionInfo', data.data.course, function(cdata){
			$('.course').val(data.data.course);
			$('.pid').html('<option value="">请选择章节</option>'+cdata.data);
			$('.pid').val(data.data.pid);
		});
		$.each(data.data, function(n, value){
			$("#edit #"+n).val(value);
		});
	});
}
function selftest(id){
	$.AMUI.progress.start();
	$.ajax({
		url:'',
		data:{
			'id':id
		},
		type:'post',
		dataType:'json',
		success:function(data) {
			if(data.status =='1'){
				str = '';
				$('.title').html($('#'+id).html());
				$.each(data.data, function(n, value){
					if (value.total != '0') {
						value.process = value.process == null ? 0 : Math.ceil(value.process*100);
						str += '<div class="am-u-md-12">';
						str += 	'<div class="am-u-md-6">'+value.title+'</div>';
						str += 	'<div class="am-u-md-3">';
						str += 		'<div class="am-progress am-progress-striped">';
						str += 			'<div class="am-progress-bar" style="width: '+value.process+'%">'+value.process+'%</div>';
						str += 		'</div>';
						str += 	'</div>';
						str += 	'<div class="am-u-md-3">';
						if (value.process == 100) {
							str += 		'<a href="report?id='+value.id+'" class="am-btn am-btn-success am-btn-xs">查看报告</a>';
						} else {
							str += 		'<a href="start_self_test?id='+value.id+'" class="am-btn am-btn-primary am-btn-xs">进入测试</a>';
						}
						str += 	'</div>';
						str += '</div>';
					}
				});
				str = str == '' ? '<div class="am-text-center">暂时没有自测题需要测试</div>' : str;
				$('.detail_text').html(str);
				$.AMUI.progress.done();
			} else {
				showFail(data.msg);
				$.AMUI.progress.done();
			}
		} 
	});
	$.AMUI.progress.done();
}
function college(page, keyword){
	if(typeof(page) == 'undefined') page = 1;
	ajaxRequest('', page, keyword, function(data){
		$.each(data.data, function(n, value){
			str += '<tr id="'+value['id']+'">';
			str += '<td><input type="checkbox" class="check" name="ids" value="'+value['id']+'"></td><td class="am-hide-sm-only">'+value['id']+'</td><td style="text-align: left;">'+value['struct']+'</td><td class="am-hide-sm-only">'+value['uid']+'</td><td>'+value['title']+'</td>';
			str += '<td><div class="am-btn-group am-btn-group-xs"><a href="javascript:showEdit('+value['id']+');" data-am-modal="{target: \'#edit-modal\', closeViaDimmer: 1}"><span class="am-icon-pencil">编辑</a> <a href="javascript:deletekey(\'class\', '+value['id']+');" class="am-text-danger"><span class="am-icon-trash">删除</a></div></td></tr>';
		});
		$('.am-pagination').html(pagenav(data.total, page, keyword, 'college'));
		$('#body').html(str);
	});
}
function collegeInfo(id){
	infoRequest('../common/getClassInfoById', id, function(data){
		$.each(data.data, function(n, value){
			$('.'+n).val(value);
		});
	});
}
function classInfo(id){
	$.ajax({
		url:'../common/getClassInfo',
		data:{
			'id':id
		},
		type:'post',
		dataType:'json',
		success:function(data) {
			if(data.status =='1'){
				str = '<option value="">请选择班级</option>';
				$.each(data.data, function(n, value){
					extra = value.id == '{$data.class}' ? ' selected' : '';
					str += '<option value="'+value.id+'"'+extra+'>'+value.title+'</option>';
				});
				$('#class').html(str);
			} else {
				showFail('获取班级信息失败！');
			}
		}
	});
}

function message(page){
	if(typeof(page) == 'undefined') page = 1;
	ajaxRequest('', page, '', function(data){
		$.each(data.data, function(n, value){
			if(value['read'] == 'N') status = ' style="color:#0e90d2;"';
			else status = '';
			str += '<tr id="'+value['id']+'"'+status+'">';
			str += '<td><input type="checkbox" class="check" name="ids" value="'+value['id']+'"></td><td>'+value['content']+'</td><td class="am-hide-sm-only">'+value['create_time']+'</td>';
		});
		$('.am-pagination').html(pagenav(data.total, page, 'message'));
		$('#body').html(str);
	});
}

function deletekey(type, id){
	$('body').append('<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm">'+
	'<div class="am-modal-dialog">'+
		'<div class="am-modal-hd">确认操作</div>'+
		'<div class="am-modal-bd">'+
			'您确认要进行此操作吗？'+
		'</div>'+
		'<div class="am-modal-footer">'+
			'<span class="am-modal-btn" data-am-modal-cancel>取消</span>'+
			'<span class="am-modal-btn" data-am-modal-confirm>确定</span>'+
		'</div>'+
	'</div>'+
	'</div>');
	$('#my-confirm').modal({
		closeViaDimmer: false,
		onConfirm: function(options) {
			$.ajax({
				url:'../common/delete',
				data:{
					'table':type,
					'key':id
				},
				type:'post',
				dataType:'json',
				success:function(data) {
					if(data.status =='1'){
						if((typeof id=='number')&&id.constructor==Number){
							$('#'+id).remove();
						} else {
							for (var i = 0; i <= id.length; i++) {
								$('#'+id[i]).remove();
							}
						}
						showSuccess(data.msg);
						$.AMUI.progress.done();
					} else {
						showFail(data.msg);
						$.AMUI.progress.done();
					}
				}
			});
		}
	});
}
function read(id){
	$.AMUI.progress.start();
	$.ajax({
		url:'../my/read',
		data:{
			'key':id
		},
		type:'post',
		dataType:'json',
		success:function(data) {
			if(data.status =='1'){
				if((typeof id=='number')&&id.constructor==Number){
					$('#'+id).removeAttr('style');
				} else {
					$.each(id, function(n, value){
						$('#'+value).removeAttr('style');
					});
				}
				showSuccess(data.msg);
				$.AMUI.progress.done();
			} else {
				showFail(data.msg);
				$.AMUI.progress.done();
			}
		}
	});
}
function readAll(id){
	$.AMUI.progress.start();
	$.ajax({
		url:'../my/readall',
		type:'post',
		dataType:'json',
		success:function(data) {
			if(data.status =='1'){
				$('tr').removeAttr('style');
				showSuccess(data.msg);
				$.AMUI.progress.done();
			} else {
				showFail(data.msg);
				$.AMUI.progress.done();
			}
		}
	});
}
function showSuccess(msg){
	if(typeof(msg)=='undefined') msg = '操作成功';
	$(".admin-content").append('<div class="am-alert am-alert-success am-radius" data-am-alert style="width:300px;position:fixed;top:40px;right:10px;display:none"><button type="button" class="am-close">&times;</button><p>'+msg+'</p></div>');
	$('.am-alert-success').fadeIn();
	setTimeout(function(){
		$('.am-alert-success').fadeOut();
		$('.am-alert').remove();
	},2000);
}
function showFail(msg){
	if(typeof(msg)=='undefined') msg = '操作失败';
	$(".admin-content").append('<div class="am-alert am-alert-danger am-radius" data-am-alert style="width:300px;position:fixed;top:40px;right:10px;display:none"><button type="button" class="am-close">&times;</button><p>'+msg+'</p></div>');
	$('.am-alert-danger').fadeIn();
	setTimeout(function(){
		$('.am-alert-danger').fadeOut();
		$('.am-alert').remove();
	},2000);
}
//选中所有checkbox
function selAll(){
	if ($("input[name='selectall']").is(':checked')) {
		$("input[name='ids']").prop("checked", true);
	} else {
		$("input[name='ids']").prop("checked", false);
	}
}
//获取url中的参数
function getUrlParam(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
	var r = window.location.search.substr(1).match(reg);  //匹配目标参数
	if (r != null) return unescape(r[2]); return null; //返回参数值
}
//创建分页
function pagenav(total, page, keyword, func){
	extra = typeof(keyword)=='undefined' ? '' : ', '+keyword;
	total_page = Math.ceil(total/20);
	pre = page - 1;
	next = page + 1;
	nav = total+'/'+total_page;
	if (total == 0){
		nav += '<li class="am-disabled"><a href="#">&laquo;</a></li>';
		nav += '<li class="am-disabled"><a href="#">1</a></li>';
		nav += '<li class="am-disabled"><a href="#">&raquo;</a></li>';
		return nav;
	}
	else if (total_page == 1){
		nav += '<li class="am-disabled"><a href="#">&laquo;</a></li>';
		nav += '<li class="am-disabled"><a href="#">1</a></li>';
		nav += '<li class="am-disabled"><a href="#">&raquo;</a></li>';
		return nav;
	}
	else if (page == 1){
		nav += '<li class="am-disabled"><a href="#">&laquo;</a></li>';
		nav += '<li class="am-disabled"><a href="#">1</a></li>';
		nav += '<li><a href="javascript:'+func+'(2'+extra+');">2</a></li>';
		nav += '<li><a href="javascript:'+func+'('+total_page+extra+');">&raquo;</a></li>';
		return nav;
	}
	else if (page == total_page){
		nav += '<li><a href="javascript:'+func+'(1'+extra+');">&laquo;</a></li>';
		nav += '<li><a href="javascript:'+func+'('+pre+extra+');">'+pre+'</a></li>';
		nav += '<li class="am-disabled"><a href="javascript:'+func+'('+page+extra+');">'+page+'</a></li>';
		nav += '<li class="am-disabled"><a href="javascript:'+func+'('+total_page+extra+');">&raquo;</a></li>';
		return nav;
	}
	else if (page >= 2){
		nav += '<li><a href="javascript:'+func+'(1'+extra+');">&laquo;</a></li>';
		nav += '<li><a href="javascript:'+func+'('+pre+extra+');">'+pre+'</a></li>';
		nav += '<li class="am-disabled"><a href="javascript:'+func+'('+page+extra+');">'+page+'</a></li>';
		nav += '<li><a href="javascript:'+func+'('+next+extra+');">'+next+'</a></li>';
		nav += '<li><a href="javascript:'+func+'('+total_page+extra+');">&raquo;</a></li>';
		return nav;
	}
	else {
		nav += '<li><a href="javascript:'+func+'(1'+extra+');">&laquo;</a></li>';
		nav += '<li><a href="javascript:'+func+'('+pre+extra+');">'+pre+'</a></li>';
		nav += '<li class="am-disabled"><a href="javascript:'+func+'('+page+extra+');">'+page+'</a></li>';
		nav += '<li><a href="javascript:'+func+'('+next+extra+');">'+next+'</a></li>';
		nav += '<li><a href="javascript:'+func+'('+total_page+extra+');">&raquo;</a></li>';
		return nav;
	}
}