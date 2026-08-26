<?php
/**
 * Plantilla de pagina: Politica de privacidad (SPIRUP).
 *
 * Se aplica automaticamente a la pagina con slug "politica-de-privacidad".
 * Contenido en codigo (segun el diseno).
 *
 * @package SPIRUP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main class="spirup-main spirup-legal">
	<div class="spirup-legal__inner">

		<h1 class="spirup-legal__title">
			<span class="spirup-legal__bolt" aria-hidden="true">
				<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/imagenes/image 11.png' ); ?>" alt="">
			</span>
			Políticas de privacidad
		</h1>

		<p class="spirup-legal__intro">CIBUS CORP S.A.C., con RUC 20615736024 y domicilio en Av. Marco Puente Llanos, Asoc. Florida 1 de Califronia Mz C Lt 11. LIMA - LIMA - ATE, es el responsable del tratamiento de los datos personales que recopila a través de spirup.com (en adelante, "el Sitio").</p>
		<p class="spirup-legal__intro">Para cualquier consulta sobre esta política o el tratamiento de tus datos, puedes escribirnos a <a href="mailto:info@spirup.com">info@spirup.com</a></p>

		<h2 class="spirup-legal__h2">1. ¿Qué datos recopilamos?</h2>
		<div class="spirup-legal__tablewrap">
			<table class="spirup-legal__table">
				<thead>
					<tr><th>Momento</th><th>Datos</th><th>Ejemplo</th></tr>
				</thead>
				<tbody>
					<tr>
						<td rowspan="3" class="spirup-legal__td--moment">Compra de producto</td>
						<td>Identificación y contacto</td>
						<td>Nombre completo, DNI o RUC (para boleta/factura), correo, teléfono</td>
					</tr>
					<tr>
						<td>Envío</td>
						<td>Dirección, distrito, referencia</td>
					</tr>
					<tr>
						<td>Pago</td>
						<td>Procesado por la pasarela de pago; <em>Spir Up no almacena el número completo de tarjeta</em></td>
					</tr>
					<tr>
						<td class="spirup-legal__td--moment">Suscripción al newsletter / Quiero probarlo</td>
						<td>Contacto</td>
						<td>Correo electrónico, y nombre si lo dejas</td>
					</tr>
					<tr>
						<td class="spirup-legal__td--moment">Navegación en el Sitio</td>
						<td>Datos de uso</td>
						<td>Páginas vistas, tiempo en el sitio, origen del tráfico, tipo de dispositivo (vía Google Tag Manager / Analytics)</td>
					</tr>
					<tr>
						<td class="spirup-legal__td--moment">Reclamos</td>
						<td>Identificación y caso</td>
						<td>Nombre, DNI, correo, detalle del reclamo (Libro de Reclamaciones)</td>
					</tr>
				</tbody>
			</table>
		</div>
		<p>No solicitamos datos sensibles (salud, origen étnico, afiliación política, etc.) en ningún punto del Sitio.</p>

		<h2 class="spirup-legal__h2">2. ¿Para qué usamos tus datos?</h2>
		<ul class="spirup-legal__list">
			<li>Procesar y despachar tu pedido (Citrus Blue y Rebel Blue, en presentación individual, six-pack o twelve-pack).</li>
			<li>Emitir boleta o factura electrónica, según corresponda.</li>
			<li>Coordinar el envío con nuestro operador logístico.</li>
			<li>Responder consultas y gestionar reclamos.</li>
			<li>Enviarte comunicaciones comerciales (lanzamientos, promociones) solo si aceptaste recibirlas al suscribirte o comprar.</li>
			<li>Medir el desempeño del Sitio y mejorar la experiencia de compra (analítica web).</li>
			<li>Cumplir obligaciones legales y tributarias.</li>
		</ul>

		<h2 class="spirup-legal__h2">3. Base legal del tratamiento</h2>
		<ul class="spirup-legal__list">
			<li><strong>Ejecución de un contrato:</strong> los datos necesarios para procesar tu compra y envío.</li>
			<li><strong>Consentimiento:</strong> para comunicaciones comerciales y cookies no esenciales (puedes retirarlo cuando quieras).</li>
			<li><strong>Obligación legal:</strong> emisión de comprobantes de pago, registros tributarios.</li>
		</ul>

		<h2 class="spirup-legal__h2">4. ¿Con quién compartimos tus datos?</h2>
		<p>Compartimos información únicamente con terceros que nos ayudan a operar el Sitio, bajo acuerdos de confidencialidad y solo para los fines descritos:</p>
		<ul class="spirup-legal__list">
			<li>Pasarela de pago, para procesar el cobro.</li>
			<li>Operador logístico (ej. Shalom, Olva Courier), para el despacho de tu pedido.</li>
			<li>Proveedor de email marketing, para el envío de newsletters.</li>
			<li>Google (Google Tag Manager / Analytics), para medición de tráfico.</li>
			<li>Autoridades competentes, cuando la ley lo exija.</li>
		</ul>
		<p>No vendemos ni alquilamos tus datos personales a terceros para fines publicitarios ajenos a Spir Up.</p>

		<h2 class="spirup-legal__h2">5. Transferencia internacional de datos</h2>
		<p>Algunos de nuestros proveedores (pasarela de pago, herramientas de analítica o email marketing) pueden almacenar datos en servidores fuera del Perú. En esos casos, nos aseguramos de que el país de destino ofrezca un nivel de protección adecuado, conforme al artículo 15 de la Ley 29733, o que exista un compromiso contractual equivalente con el proveedor.</p>

		<h2 class="spirup-legal__h2">6. ¿Cuánto tiempo conservamos tus datos?</h2>
		<ul class="spirup-legal__list">
			<li>Datos de compra y comprobantes: el plazo que exige la normativa tributaria peruana (generalmente 5 años).</li>
			<li>Datos de reclamos (Libro de Reclamaciones): mínimo 2 años, según INDECOPI.</li>
			<li>Datos de newsletter: hasta que te des de baja o solicites su eliminación.</li>
			<li>Datos de navegación/cookies: según el plazo de cada cookie (ver sección 9).</li>
		</ul>

		<h2 class="spirup-legal__h2">7. Tus derechos (Derechos ARCO)</h2>
		<p>Como titular de tus datos, en cualquier momento puedes solicitar:</p>
		<ul class="spirup-legal__list">
			<li>Acceso: saber qué datos tenemos sobre ti y para qué los usamos.</li>
			<li>Rectificación: corregir datos inexactos o desactualizados.</li>
			<li>Cancelación: que eliminemos tus datos cuando ya no sean necesarios.</li>
			<li>Oposición: oponerte a un uso específico de tus datos (por ejemplo, dejar de recibir promociones).</li>
			<li>Portabilidad: recibir tus datos en un formato estructurado para transferirlos a otro responsable.</li>
		</ul>
		<p>Puedes ejercer estos derechos escribiendo a <a href="mailto:info@spirup.com">info@spirup.com</a>, indicando tu nombre, el derecho que deseas ejercer y adjuntando copia de tu DNI. Responderemos dentro del plazo que establece la ley (15 días hábiles).</p>
		<p>Si consideras que no atendimos tu solicitud correctamente, puedes presentar una reclamación ante la Autoridad Nacional de Protección de Datos Personales (ANPD), del Ministerio de Justicia.</p>

		<h2 class="spirup-legal__h2">8. Cookies y tecnologías similares</h2>
		<p>El Sitio usa cookies propias y de terceros para:</p>
		<ul class="spirup-legal__list">
			<li>Esenciales: mantener tu carrito de compra activo durante la sesión.</li>
			<li>Analíticas: entender cómo navegas el Sitio (Google Analytics / GTM).</li>
			<li>Marketing (si aplica): medir el rendimiento de campañas (Meta Pixel, etc.).</li>
		</ul>
		<p>Puedes administrar o desactivar las cookies no esenciales desde la configuración de tu navegador o desde el banner de cookies del Sitio. Desactivarlas no afecta tu posibilidad de comprar, pero puede limitar algunas funciones.</p>

		<h2 class="spirup-legal__h2">9. Seguridad de la información</h2>
		<p>Implementamos medidas técnicas y organizativas razonables para proteger tus datos (conexión cifrada HTTPS, acceso restringido a la información de clientes, pasarela de pago certificada PCI-DSS). Ningún sistema es 100% infalible, pero ante cualquier incidente de seguridad que afecte tus datos, te lo notificaremos siguiendo los plazos que exige la normativa vigente.</p>

		<h2 class="spirup-legal__h2">10. Menores de edad</h2>
		<p>El Sitio no está dirigido a menores de 18 años. No recopilamos intencionalmente datos de menores. Si detectamos que un menor nos proporcionó datos sin autorización de sus padres o tutores, procederemos a eliminarlos.</p>

		<h2 class="spirup-legal__h2">11. Cambios a esta política</h2>
		<p>Podemos actualizar esta política para reflejar cambios legales, operativos o en nuestros proveedores. La versión vigente siempre estará publicada en esta página, con la fecha de última actualización.</p>

		<p class="spirup-legal__contact">Si tiene alguna pregunta sobre nuestras prácticas de privacidad o sobre la presente Política de privacidad, o bien si desea ejercer cualquiera de sus derechos, puede llamarnos por teléfono, enviar un correo electrónico a <a href="mailto:info@spirup.com">info@spirup.com</a> o ponerse en contacto con nosotros a través de la dirección CIBUS CORP S.A.C.,&nbsp; Av. Marco Puente Llanos,&nbsp; Asoc. Florida 1 de Califronia Mz C Lt 11. LIMA - LIMA - ATE</p>

		<?php
		/* Con el respaldo de: logos en orden (ministerio, proinnovate, startup, cayetano, bioincuba).
		   Sube los PNG a /imagenes/ con estos nombres; solo se muestran los que existan. */
		$sp_respaldos = array(
			array( 'produce',     'Ministerio de la Producción del Perú' ),
			array( 'proinnovate', 'ProInnóvate' ),
			array( 'startup',     'StartUp Perú' ),
			array( 'cayetano',    'Universidad Peruana Cayetano Heredia' ),
			array( 'bioincuba',   'Bioincuba' ),
		);
		$sp_dir = get_stylesheet_directory();
		$sp_url = get_stylesheet_directory_uri();
		?>
		<div class="spirup-respaldo">
			<p class="spirup-respaldo__label">Con el respaldo de:</p>
			<div class="spirup-respaldo__logos">
				<?php foreach ( $sp_respaldos as $sp_r ) :
					$sp_file = '/imagenes/respaldo-' . $sp_r[0] . '.png';
					if ( file_exists( $sp_dir . $sp_file ) ) : ?>
						<img src="<?php echo esc_url( $sp_url . $sp_file ); ?>" alt="<?php echo esc_attr( $sp_r[1] ); ?>">
					<?php endif; endforeach; ?>
			</div>
		</div>
	</div>

	<div class="spirup-legal__law">
		<div class="spirup-legal__law-inner">
			<strong>Esta política se rige por la Ley N° 29733, Ley de Protección de Datos Personales, su reglamento (D.S. N° 016-2024-JUS) y demás normativa peruana aplicable al comercio electrónico.</strong>
		</div>
	</div>
</main>

<?php
get_footer();
