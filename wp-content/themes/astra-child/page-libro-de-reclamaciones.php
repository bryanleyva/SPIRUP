<?php
/**
 * Plantilla de pagina: Libro de reclamaciones (SPIRUP).
 *
 * Formulario (3 secciones) + modal de exito. Slug "libro-de-reclamaciones".
 *
 * @package SPIRUP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$tipos_doc = array( 'DNI', 'Carné de extranjería', 'Pasaporte', 'RUC' );
$departamentos = array( 'Amazonas', 'Áncash', 'Apurímac', 'Arequipa', 'Ayacucho', 'Cajamarca', 'Callao', 'Cusco', 'Huancavelica', 'Huánuco', 'Ica', 'Junín', 'La Libertad', 'Lambayeque', 'Lima', 'Loreto', 'Madre de Dios', 'Moquegua', 'Pasco', 'Piura', 'Puno', 'San Martín', 'Tacna', 'Tumbes', 'Ucayali' );
$provincias = array( 'Lima', 'Callao', 'Otra' );
$distritos = array( 'La Molina', 'Ate', 'Santa Anita', 'San Borja', 'Santiago de Surco', 'San Luis', 'Surquillo', 'Miraflores', 'San Isidro', 'Barranco', 'Lince', 'La Victoria', 'Jesús María', 'Magdalena', 'Pueblo Libre', 'San Miguel', 'Chorrillos', 'San Juan de Miraflores', 'Cercado de Lima', 'Breña', 'Los Olivos', 'Independencia', 'San Martín de Porres', 'Rímac', 'Comas', 'Carabayllo', 'Puente Piedra', 'Villa El Salvador', 'Villa María del Triunfo', 'San Juan de Lurigancho', 'El Agustino', 'Bellavista', 'La Perla', 'Ventanilla', 'Lurín', 'Pachacámac', 'Cieneguilla', 'Chaclacayo', 'Chosica', 'Ancón', 'Santa Rosa', 'Otro' );
$medios_pago = array( 'Tarjeta de crédito/débito', 'Yape', 'Plin', 'Efectivo / contra entrega', 'Otro' );

$opts = function ( $arr ) {
	$html = '<option value="" selected disabled>Seleccione</option>';
	foreach ( $arr as $o ) {
		$html .= '<option value="' . esc_attr( $o ) . '">' . esc_html( $o ) . '</option>';
	}
	return $html;
};
?>

<main class="spirup-main spirup-lr">
	<div class="spirup-lr__inner">

		<h1 class="spirup-legal__title">
			<span class="spirup-legal__bolt" aria-hidden="true">
				<svg viewBox="0 0 24 24" fill="currentColor"><path d="M13 2 3.5 13.6c-.4.5-.05 1.2.58 1.2H10l-1.7 6.9c-.16.66.66 1.1 1.12.6L20.5 10c.4-.5.05-1.2-.58-1.2H14l1.6-6.2c.17-.66-.65-1.12-1.12-.6z"/></svg>
			</span>
			Libro de reclamaciones
		</h1>
		<p class="spirup-lr__intro">CIBUS CORP S.A.C.<br>RUC: 20615736024<br>Av. Marco Puente Llanos, Asoc. Florida 1 de California Mz C Lt 11. Lima - Lima - Ate<br>Tienda Online: La tienda virtual no está dirigida a menores de edad.</p>

		<form class="spirup-lr__form" id="spirup-lr-form" novalidate>

			<section class="spirup-lr__sec">
				<h2 class="spirup-lr__h2">1. Identificación del consumidor reclamante</h2>
				<div class="spirup-lr__grid">
					<div class="spirup-field"><label>Nombres <span>*</span></label><input type="text" name="nombres" required></div>
					<div class="spirup-field"><label>Apellidos <span>*</span></label><input type="text" name="apellidos" required></div>
					<div class="spirup-field"><label>Correo <span>*</span></label><input type="email" name="correo" required></div>
					<div class="spirup-field"><label>Teléfono <span>*</span></label><input type="tel" name="telefono" required></div>
					<div class="spirup-field"><label>Tipo de documento</label><select name="tipo_doc"><?php echo $opts( $tipos_doc ); // phpcs:ignore ?></select></div>
					<div class="spirup-field"><label>Número de documento <span>*</span></label><input type="text" name="num_doc" required></div>
					<div class="spirup-field"><label>Departamento <span>*</span></label><select name="departamento" required><?php echo $opts( $departamentos ); // phpcs:ignore ?></select></div>
					<div class="spirup-field"><label>Provincia <span>*</span></label><select name="provincia" required><?php echo $opts( $provincias ); // phpcs:ignore ?></select></div>
					<div class="spirup-field"><label>Distrito <span>*</span></label><select name="distrito" required><?php echo $opts( $distritos ); // phpcs:ignore ?></select></div>
					<div class="spirup-field"><label>Dirección <span>*</span></label><input type="text" name="direccion" required></div>
				</div>
			</section>

			<section class="spirup-lr__sec spirup-lr__sec--gray">
				<div class="spirup-lr__wrap">
					<h2 class="spirup-lr__h2">2. Identificación del bien contratado</h2>
					<label class="spirup-radio"><input type="radio" name="bien" value="producto" checked><span></span>Producto</label>
					<div class="spirup-lr__grid">
						<div class="spirup-field"><label>Medio de pago <span>*</span></label><select name="medio_pago" required><?php echo $opts( $medios_pago ); // phpcs:ignore ?></select></div>
						<div class="spirup-field"><label>Fecha <span>*</span></label><input type="date" name="fecha" required></div>
					</div>
					<div class="spirup-field"><label>Monto reclamado <span>*</span></label><input type="text" name="monto" placeholder="S/" required></div>
					<div class="spirup-field"><label>Descripción <span>*</span></label><textarea name="descripcion" rows="6" required></textarea></div>
				</div>
			</section>

			<section class="spirup-lr__sec">
				<h2 class="spirup-lr__h2">3. Detalle de la reclamación y pedido del consumidor</h2>
				<div class="spirup-lr__radios">
					<label class="spirup-radio"><input type="radio" name="tipo" value="reclamo" checked><span></span>Reclamo</label>
					<label class="spirup-radio"><input type="radio" name="tipo" value="queja"><span></span>Queja</label>
				</div>
				<p class="spirup-lr__hint">Disconformidad relacionada a los productos o servicios.</p>
				<div class="spirup-field"><label>Detalle <span>*</span></label><textarea name="detalle" rows="5" required></textarea></div>
				<div class="spirup-field"><label>Pedido del reclamante <span>*</span></label><textarea name="pedido" rows="5" required></textarea></div>

				<label class="spirup-lr__file">
					<input type="file" name="adjunto" accept=".jpg,.jpeg,.png,.pdf" hidden>
					<span class="spirup-lr__file-btn">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 15V3"/><path d="m7 8 5-5 5 5"/><path d="M5 15v4a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4"/></svg>
						Adjuntar archivos
					</span>
					<small>JPG, PNG o PDF tamaño máx de 2 Mb.</small>
				</label>

				<label class="spirup-check"><input type="checkbox" name="conforme"><span></span>Me encuentro conforme con los términos de mi reclamo o queja</label>
				<label class="spirup-check"><input type="checkbox" name="acepto" checked><span></span>Declaro que he leído y acepto la política de privacidad de Spir Up</label>

				<p class="spirup-lr__note">El proveedor deberá dar respuesta al reclamo en un plazo no mayor de quince (15) días hábiles.</p>

				<div class="spirup-lr__submit">
					<button type="submit" class="spirup-lr__btn">Enviar ↗</button>
				</div>
			</section>
		</form>
	</div>

	<?php /* Modal de exito */ ?>
	<div class="spirup-lr__modal" id="spirup-lr-modal" hidden>
		<div class="spirup-lr__modal-card" role="dialog" aria-modal="true" aria-labelledby="spirup-lr-modal-title">
			<button type="button" class="spirup-lr__modal-close" aria-label="Cerrar">&times;</button>
			<span class="spirup-lr__badge" aria-hidden="true">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m8.5 12.5 2.5 2.5 5-5.5"/></svg>
			</span>
			<h3 id="spirup-lr-modal-title">¡Tu reclamo fue enviado con éxito!</h3>
			<p class="spirup-lr__modal-meta">N.° de reclamo: <strong id="spirup-lr-num">RC-2026-00123</strong></p>
			<p class="spirup-lr__modal-meta">Fecha de registro: <strong id="spirup-lr-date">dd/mm/aaaa</strong></p>
			<p>Hemos enviado una copia de tu reclamo al correo electrónico que registraste. De acuerdo con el Código de Protección y Defensa del Consumidor, tienes una respuesta en un plazo máximo de 15 días calendario.</p>
			<p class="spirup-lr__modal-thanks">Gracias por tu confianza.<br>Estamos para ayudarte.</p>
		</div>
	</div>
</main>

<script>
( function () {
	var form  = document.getElementById( 'spirup-lr-form' );
	var modal = document.getElementById( 'spirup-lr-modal' );
	if ( ! form || ! modal ) { return; }
	var close = modal.querySelector( '.spirup-lr__modal-close' );

	function pad( n, l ) { n = '' + n; while ( n.length < l ) { n = '0' + n; } return n; }

	form.addEventListener( 'submit', function ( e ) {
		e.preventDefault();
		if ( ! form.checkValidity() ) { form.reportValidity(); return; }
		var now = new Date();
		var num = pad( Math.floor( Math.random() * 100000 ), 5 );
		document.getElementById( 'spirup-lr-num' ).textContent = 'RC-' + now.getFullYear() + '-' + num;
		document.getElementById( 'spirup-lr-date' ).textContent = pad( now.getDate(), 2 ) + '/' + pad( now.getMonth() + 1, 2 ) + '/' + now.getFullYear();
		modal.hidden = false;
		document.body.style.overflow = 'hidden';
	} );

	function hide() { modal.hidden = true; document.body.style.overflow = ''; form.reset(); }
	close.addEventListener( 'click', hide );
	modal.addEventListener( 'click', function ( e ) { if ( e.target === modal ) { hide(); } } );
	document.addEventListener( 'keydown', function ( e ) { if ( 'Escape' === e.key && ! modal.hidden ) { hide(); } } );

	// Mostrar el nombre del archivo adjunto al seleccionarlo.
	var file = form.querySelector( 'input[type="file"]' );
	if ( file ) {
		file.addEventListener( 'change', function () {
			var btn = form.querySelector( '.spirup-lr__file-btn' );
			if ( file.files.length ) { btn.lastChild.textContent = ' ' + file.files[0].name; }
		} );
	}
} )();
</script>

<?php
get_footer();
