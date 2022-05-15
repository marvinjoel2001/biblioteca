<script>
$(document).ready(function(){
	$('.btn-exit-system').on('click', function(e){
		e.preventDefault();
		var Token=$(this).attr('href');
		swal({
		  	title: 'Estas Seguro?',
		  	text: "La sesion actual se cerrara y tendras que iniciar nuevamente",
		  	type: 'warning',
		  	showCancelButton: true,
		  	confirmButtonColor: '#03A9F4',
		  	cancelButtonColor: '#F44336',
		  	confirmButtonText: '<i class="zmdi zmdi-run"></i> Si, Exit!',
		  	cancelButtonText: '<i class="zmdi zmdi-close-circle"></i> No, Cancelar!'
		}).then(function () {
			$.ajax({
				url:'<?php echo SERVERURL; ?>ajax/loginAjax.php?Token='+Token,
				success:function(data){
					if (data="true" || data=="true" || data==="true") {
						window.location.href="<?php echo SERVERURL; ?>login/";
					} else {
						swal(
							"Ocurrio un Error",
							"No se pudo cerrar la sesion",
							"error"
						);
					}
				}
			});
		});
	});
})
</script>