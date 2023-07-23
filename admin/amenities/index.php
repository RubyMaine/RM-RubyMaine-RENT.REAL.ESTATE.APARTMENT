<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline rounded-0 card-primary">
	<div class="card-header">
		<h3 class="card-title"> Список удобств </h3>
		<div class="card-tools">
			<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary" style="border-radius: 5px;"><span class="fas fa-plus"></span> Добавить новое удобство </a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped" id="list">
				<colgroup>
					<col width="5%">
					<col width="20%">
					<col width="25%">
					<col width="25%">
					<col width="10%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th> # ID: </th>
						<th> Дата создания: </th>
						<th> Название: </th>
						<th> Тип: </th>
						<th> Состояние: </th>
						<th> Действие: </th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT * from `amenity_list` where delete_flag = 0 order by `name` asc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
							<td><?php echo $row['name'] ?></td>
							<td ><?= $row['type'] == 1 ? "Indoor" : "Outdoor" ?></td>
							<td class="text-center">
                                <?php if($row['status'] == 1): ?>
                                    <span class="badge badge-success px-3 rounded-pill"> Активный </span>
                                <?php else: ?>
                                    <span class="badge badge-danger px-3 rounded-pill"> Неактивный </span>
                                <?php endif; ?>
                            </td>
							<td align="center">
								 <button type="button" class="btn btn-flat p-1 btn-primary btn-sm dropdown-toggle dropdown-icon" style="border-radius: 5px;" data-toggle="dropdown"><span class="fas fa-exclamation-triangle"></span> Действие <span class="sr-only">Toggle Dropdown</span></button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item edit_data" href=javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Редактировать </a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Удалить </a>
				                  </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Вы уверены, что хотите навсегда удалить это Удобство?","delete_amenity",[$(this).attr('data-id')])
		})
		$('#create_new').click(function(){
			uni_modal("<i class='fa fa-plus'></i> Добавить новое удобство ","amenities/manage_amenity.php")
		})
		$('.edit_data').click(function(){
			uni_modal("<i class='fa fa-edit'></i> Обновить информацию об удобствах ","amenities/manage_amenity.php?id="+$(this).attr('data-id'))
		})
		$('.table').dataTable({
			columnDefs: [
					{ orderable: false, targets: [4,5] }
			],
			order:[0,'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})
	function delete_amenity($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_amenity",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>