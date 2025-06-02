// Estilos comunes para los botones
const estilosBoton = `
    padding: 0.75rem 2rem;
    font-size: 1rem;
    font-weight: bold;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
`;

const estilosBotonAceptar = `
    ${estilosBoton}
    background: #16a34a;
    color: #fff;
    &:hover {
        background: #15803d;
    }
`;

const estilosBotonRechazar = `
    ${estilosBoton}
    background: #dc2626;
    color: #fff;
    &:hover {
        background: #b91c1c;
    }
`;

const estilosBotonVolver = `
    ${estilosBoton}
    width: 100%;
    background: #666;
    color: #fff;
    margin-top: 1rem;
    &:hover {
        background: #555;
    }
`;

// Contenido inicial del banner
const contenidoInicial = `
    <div style="background:#fff; padding:2rem; border-radius:8px; box-shadow:0 0 20px rgba(0,0,0,0.1); text-align:center; max-width:600px;">
        <h2 style="font-size:1.5rem; font-weight:bold; color:#333; margin-bottom:1.5rem;">Este sitio usa cookies</h2>
        <p style="font-size:1rem; color:#555; margin-bottom:1rem;">Debes aceptar las cookies para poder navegar por la web.</p>
        <p style="margin:1rem 0; font-size:0.9rem; color:#666;">
            Consulta nuestra 
            <a href="#" style="color:#16a34a; text-decoration:underline; font-weight:500;" id="show-cookies">Política de Cookies</a> y 
            <a href="#" style="color:#16a34a; text-decoration:underline; font-weight:500;" id="show-legal">Aviso Legal</a>
        </p>
        <div style="display:flex; gap:1.5rem; justify-content:center; margin-top:1.5rem;">
            <button id="accept-cookies" style="${estilosBotonAceptar}">
                Aceptar cookies
            </button>
            <button id="reject-cookies" style="${estilosBotonRechazar}">
                Rechazar cookies
            </button>
        </div>
    </div>
`;

// Función para crear el overlay de bloqueo
function crearOverlayCookies() {
    const superposicion = document.createElement('div');
    superposicion.id = 'cookie-overlay';
    superposicion.style.position = 'fixed';
    superposicion.style.top = 0;
    superposicion.style.left = 0;
    superposicion.style.width = '100vw';
    superposicion.style.height = '100vh';
    superposicion.style.background = 'rgba(255,255,255,0.75)';
    superposicion.style.backdropFilter = 'blur(5px)';
    superposicion.style.zIndex = 99999;
    superposicion.style.display = 'flex';
    superposicion.style.alignItems = 'center';
    superposicion.style.justifyContent = 'center';

    superposicion.innerHTML = contenidoInicial;
    document.body.appendChild(superposicion);

    // Contenido de la política de cookies
    const contenidoCookies = `
        <div style="background:#fff; padding:2rem; border-radius:8px; max-width:800px;">
            <h2 style="font-size:1.5rem; font-weight:bold; color:#333; margin-bottom:1.5rem; text-align:center;">Política de Cookies</h2>
            <div style="text-align:left; max-height:60vh; overflow-y:auto; padding-right:1rem; line-height:1.6; font-size:0.9rem;">
                <p style="margin-bottom:1rem">
                    Las cookies, en función de su permanencia, pueden dividirse en cookies de sesión o permanentes. Las que expiran cuando el usuario cierra el navegador. Las que expiran en función de cuando se cumpla el objetivo para el que sirven (por ejemplo, para que el usuario se mantenga identificado en los servicios de BIOESPACIO BIENESTAR SL) o bien cuando se borran manualmente.
                </p>

                <h3 style="font-size:1.4rem; font-weight:600; color:#444; margin:1.5rem 0 1rem;">Cookies utilizadas en este sitio</h3>
                <div style="overflow-x:auto; margin-bottom:1.5rem;">
                    <table style="width:100%; border-collapse:collapse; margin:1rem 0; background:#fff;">
                        <thead>
                            <tr style="background:#f8f9fa;">
                                <th style="padding:12px; border:1px solid #e2e8f0; text-align:left;">Nombre</th>
                                <th style="padding:12px; border:1px solid #e2e8f0; text-align:left;">Tipo</th>
                                <th style="padding:12px; border:1px solid #e2e8f0; text-align:left;">Caducidad</th>
                                <th style="padding:12px; border:1px solid #e2e8f0; text-align:left;">Finalidad</th>
                                <th style="padding:12px; border:1px solid #e2e8f0; text-align:left;">Clase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding:12px; border:1px solid #e2e8f0;">__utma</td>
                                <td style="padding:12px; border:1px solid #e2e8f0;">De Terceros (Google Analytics)</td>
                                <td style="padding:12px; border:1px solid #e2e8f0;">2 años</td>
                                <td style="padding:12px; border:1px solid #e2e8f0;">Se usa para distinguir usuarios y sesiones.</td>
                                <td style="padding:12px; border:1px solid #e2e8f0;">No Exenta</td>
                            </tr>
                            <tr>
                                <td style="padding:12px; border:1px solid #e2e8f0;">__utmb</td>
                                <td style="padding:12px; border:1px solid #e2e8f0;">De Terceros (Google Analytics)</td>
                                <td style="padding:12px; border:1px solid #e2e8f0;">30 minutos</td>
                                <td style="padding:12px; border:1px solid #e2e8f0;">Se usa para determinar nuevas sesiones o visitas.</td>
                                <td style="padding:12px; border:1px solid #e2e8f0;">No Exenta</td>
                            </tr>
                            <tr>
                                <td style="padding:12px; border:1px solid #e2e8f0;">__utmc</td>
                                <td style="padding:12px; border:1px solid #e2e8f0;">De Terceros (Google Analytics)</td>
                                <td style="padding:12px; border:1px solid #e2e8f0;">Al finalizar la sesión</td>
                                <td style="padding:12px; border:1px solid #e2e8f0;">Se configura para su uso con Urchin.</td>
                                <td style="padding:12px; border:1px solid #e2e8f0;">No Exenta</td>
                            </tr>
                            <tr>
                                <td style="padding:12px; border:1px solid #e2e8f0;">__utmz</td>
                                <td style="padding:12px; border:1px solid #e2e8f0;">De Terceros (Google Analytics)</td>
                                <td style="padding:12px; border:1px solid #e2e8f0;">6 meses</td>
                                <td style="padding:12px; border:1px solid #e2e8f0;">Almacena el origen o la campaña que explica cómo el usuario ha llegado hasta la página web.</td>
                                <td style="padding:12px; border:1px solid #e2e8f0;">No Exenta</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3 style="font-size:1.4rem; font-weight:600; color:#444; margin:1.5rem 0 1rem;">Clasificación según su objetivo</h3>
                <ul style="list-style-type:disc; padding-left:20px;">
                    <li>
                        <strong>Cookies de rendimiento:</strong> Recuerdan sus preferencias para las herramientas que se encuentran en los servicios, por lo que no tiene que volver a configurar el servicio cada vez que usted visita. Ejemplo: ajustes de volumen, velocidades de transmisión, objetos guardados en el "carrito de la compra".
                    </li>
                    <li>
                        <strong>Cookies de geo-localización:</strong> Se utilizan para averiguar en qué país se encuentra cuando se solicita un servicio. Son totalmente anónimas y solo se utilizan para orientar el contenido a su ubicación.
                    </li>
                    <li>
                        <strong>Cookies de registro:</strong> Se generan una vez que el usuario se ha registrado o ha abierto su sesión, y se utilizan para identificarle en los servicios. Permiten mantener al usuario identificado, comprobar si está autorizado para acceder a ciertos servicios, y pueden estar asociadas a redes sociales.
                    </li>
                    <li>
                        <strong>Cookies de analíticas:</strong> Herramientas de proveedores externos generan una cookie analítica en el ordenador del usuario para identificar de forma anónima al visitante, contabilizar el número de visitantes y su tendencia, identificar los contenidos más visitados, y saber si el usuario es nuevo o repite visita. <br>
                        <em>Importante:</em> Salvo que el usuario decida registrarse, la cookie nunca irá asociada a ningún dato personal que pueda identificarle.
                    </li>
                    <li>
                        <strong>Cookies de publicidad:</strong> Permiten ampliar la información de los anuncios mostrados a cada usuario anónimo, almacenar la duración o frecuencia de visualización, la interacción, o los patrones de navegación y/o comportamientos del usuario para ofrecer publicidad afín a sus intereses.
                    </li>
                    <li>
                        <strong>Cookies publicitarias de terceros:</strong> Los anunciantes pueden servir anuncios a través de terceros ("Ad-Servers"). Estos terceros pueden almacenar cookies enviadas desde los servicios de BIOESPACIO BIENESTAR SL y acceder a los datos que en ellas se guardan. <br>
                        Más información: <a href="http://www.google.es/policies/privacy/ads/#toc-doubleclick" target="_blank" class="text-green-700 underline">Doubleclick (Google)</a>
                    </li>
                </ul>

                <h3 style="font-size:1.4rem; font-weight:600; color:#444; margin:1.5rem 0 1rem;">¿Cómo puedo deshabilitar las cookies en mi navegador?</h3>
                <p>
                    Se pueden configurar los diferentes navegadores para avisar al usuario de la recepción de cookies y, si se desea, impedir su instalación en el equipo. Asimismo, el usuario puede revisar en su navegador qué cookies tiene instaladas y cuál es el plazo de caducidad de las mismas, pudiendo eliminarlas.
                </p>
                <ul style="list-style-type:disc; padding-left:20px;">
                    <li>Google Chrome: <a href="https://support.google.com/chrome/answer/95647?hl=es" target="_blank" class="text-green-700 underline">https://support.google.com/chrome/answer/95647?hl=es</a></li>
                    <li>Internet Explorer: <a href="http://windows.microsoft.com/es-es/windows-vista/cookies-frequently-asked-questions" target="_blank" class="text-green-700 underline">http://windows.microsoft.com/es-es/windows-vista/cookies-frequently-asked-questions</a></li>
                    <li>Mozilla Firefox: <a href="http://support.mozilla.org/es/kb/habilitar-y-deshabilitar-cookies-que-los-sitios-we" target="_blank" class="text-green-700 underline">http://support.mozilla.org/es/kb/habilitar-y-deshabilitar-cookies-que-los-sitios-we</a></li>
                    <li>Safari: <a href="http://www.apple.com/es/privacy/use-of-cookies/" target="_blank" class="text-green-700 underline">http://www.apple.com/es/privacy/use-of-cookies/</a></li>
                    <li>Opera: <a href="http://help.opera.com/Windows/11.50/es-ES/cookies.html" target="_blank" class="text-green-700 underline">http://help.opera.com/Windows/11.50/es-ES/cookies.html</a></li>
                </ul>
                <p>
                    Si desea dejar de ser seguido por Google Analytics visite: <a href="http://tools.google.com/dlpage/gaoptout" target="_blank" class="text-green-700 underline">http://tools.google.com/dlpage/gaoptout</a>
                </p>

                <h3 style="font-size:1.4rem; font-weight:600; color:#444; margin:1.5rem 0 1rem;">Para saber más sobre las cookies</h3>
                <ul style="list-style-type:disc; padding-left:20px;">
                    <li><a href="http://www.youronlinechoices.com/es/" target="_blank" class="text-green-700 underline">Publicidad online basada en el comportamiento y privacidad online</a></li>
                    <li><a href="http://www.google.com/analytics/learn/privacy.html" target="_blank" class="text-green-700 underline">Protección de datos de Google Analytics</a></li>
                    <li><a href="https://developers.google.com/analytics/devguides/collection/analyticsjs/cookie-usage?hl=es#analyticsjs" target="_blank" class="text-green-700 underline">Cómo usa Google Analytics las cookies</a></li>
                </ul>

                <h3 style="font-size:1.4rem; font-weight:600; color:#444; margin:1.5rem 0 1rem;">Actualizaciones y cambios en la política de privacidad/cookies</h3>
                <p>
                    Las webs de BIOESPACIO BIENESTAR SL pueden modificar esta Política de Cookies en función de exigencias legislativas, reglamentarias, o con la finalidad de adaptar dicha política a las instrucciones dictadas por la Agencia Española de Protección de Datos, por ello se aconseja a los usuarios que la visiten periódicamente.
                    <br>
                    Cuando se produzcan cambios significativos en esta Política de Cookies, estos se comunicarán a los usuarios bien mediante la web o a través de correo electrónico a los usuarios registrados.
                </p>
            </div>
            <div style="border-top:2px solid #eee; margin-top:2rem; padding-top:1.5rem;">
                <div style="display:flex; gap:1.5rem; justify-content:center; margin-bottom:1.5rem;">
                    <button id="accept-cookies" style="${estilosBotonAceptar}">
                        Aceptar cookies
                    </button>
                    <button id="reject-cookies" style="${estilosBotonRechazar}">
                        Rechazar cookies
                    </button>
                </div>
                <button id="back-button" style="${estilosBotonVolver}">
                    ← Volver al inicio
                </button>
            </div>
        </div>
    `;

    // Contenido del aviso legal
    const contenidoLegal = `
        <div style="background:#fff; padding:2rem; border-radius:8px; max-width:800px;">
            <h2 style="font-size:1.5rem; font-weight:bold; color:#333; margin-bottom:1.5rem; text-align:center;">Aviso Legal</h2>
            <div style="text-align:left; max-height:60vh; overflow-y:auto; padding-right:1rem; line-height:1.6; font-size:0.9rem;">
                <p style="margin-bottom:1rem">
                    En este espacio, el USUARIO, podrá encontrar toda la información relativa a los términos y condiciones legales que definen las relaciones entre los usuarios y nosotros como responsables de esta web. Como usuario, es importante que conozcas estos términos antes de continuar tu navegación.
                </p>

                <h3 style="font-size:1.4rem; font-weight:600; color:#444; margin:1.5rem 0 1rem;">Condiciones Generales de Uso</h3>
                <p>
                    Las presentes Condiciones Generales regulan el uso (incluyendo el mero acceso) de las páginas de la web, integrantes del sitio web de incluidos los contenidos y servicios puestos a disposición en ellas. Toda persona que acceda a la web, ("Usuario") acepta someterse a las Condiciones Generales vigentes en cada momento del portal.
                </p>

                <h3 style="font-size:1.4rem; font-weight:600; color:#444; margin:1.5rem 0 1rem;">Datos personales que recabamos y cómo lo hacemos</h3>
                <p>
                    Leer <a href="#privacidad" class="text-green-700 underline">Política de Privacidad</a>
                </p>

                <h3 style="font-size:1.4rem; font-weight:600; color:#444; margin:1.5rem 0 1rem;">Compromisos y obligaciones de los usuarios</h3>
                <p>
                    El Usuario queda informado, y acepta, que el acceso a la presente web no supone, en modo alguno, el inicio de una relación comercial con BIOESPACIO BIENESTAR SL. De esta forma, el usuario se compromete a utilizar el sitio Web, sus servicios y contenidos sin contravenir la legislación vigente, la buena fe y el orden público.
                    <br>
                    Queda prohibido el uso de la web, con fines ilícitos o lesivos, o que, de cualquier forma, puedan causar perjuicio o impedir el normal funcionamiento del sitio web. Respecto de los contenidos de esta web, se prohíbe: Su reproducción, distribución o modificación, total o parcial, a menos que se cuente con la autorización de sus legítimos titulares; cualquier vulneración de los derechos del prestador o de los legítimos titulares; su utilización para fines comerciales o publicitarios.
                </p>
                <p>
                    En la utilización de la web, el Usuario se compromete a no llevar a cabo ninguna conducta que pudiera dañar la imagen, los intereses y los derechos de BIOESPACIO BIENESTAR SL o de terceros o que pudiera dañar, inutilizar o sobrecargar el portal o que impidiera, de cualquier forma, la normal utilización de la web. No obstante, el Usuario debe ser consciente de que las medidas de seguridad de los sistemas informáticos en Internet no son enteramente fiables y que, por tanto no puede garantizar la inexistencia de virus u otros elementos que puedan producir alteraciones en los sistemas informáticos (software y hardware) del Usuario o en sus documentos electrónicos y ficheros contenidos en los mismos.
                </p>

                <h3 style="font-size:1.4rem; font-weight:600; color:#444; margin:1.5rem 0 1rem;">Medidas de seguridad</h3>
                <p>
                    Los datos personales comunicados por el usuario a BIOESPACIO BIENESTAR SL pueden ser almacenados en bases de datos automatizadas o no, cuya titularidad corresponde en exclusiva a BIOESPACIO BIENESTAR SL, asumiendo ésta todas las medidas de índole técnica, organizativa y de seguridad que garantizan la confidencialidad, integridad y calidad de la información contenida en las mismas de acuerdo con lo establecido en la normativa vigente en protección de datos.
                    <br>
                    La comunicación entre los usuarios y BIOESPACIO BIENESTAR SL utiliza un canal seguro, y los datos transmitidos son cifrados gracias a protocolos https, por tanto, garantizamos las mejores condiciones de seguridad para que la confidencialidad de los usuarios esté garantizada.
                </p>

                <h3 style="font-size:1.4rem; font-weight:600; color:#444; margin:1.5rem 0 1rem;">Reclamaciones</h3>
                <p>
                    BIOESPACIO BIENESTAR SL informa que existen hojas de reclamación a disposición de usuarios y clientes. El Usuario podrá realizar reclamaciones solicitando su hoja de reclamación o remitiendo un correo electrónico a bioespaciobienestar@gmail.com indicando su nombre y apellidos, el servicio y/o producto adquirido y exponiendo los motivos de su reclamación.
                    <br>
                    El usuario/comprador podrá notificarnos la reclamación, bien a través de correo electrónico a: bioespaciobienestar@gmail.com, si lo desea adjuntando el siguiente formulario de reclamación: El servicio/producto: Adquirido el día: Nombre del usuario: Domicilio del usuario: Firma del usuario (solo si se presenta en papel): Fecha: Motivo de la reclamación:
                </p>

                <h3 style="font-size:1.4rem; font-weight:600; color:#444; margin:1.5rem 0 1rem;">Plataforma de resolución de conflictos</h3>
                <p>
                    Por si puede ser de tu interés, para someter tus reclamaciones puedes utilizar también la plataforma de resolución de litigios que facilita la Comisión Europea y que se encuentra disponible en el siguiente enlace: <a href="http://ec.europa.eu/consumers/odr/" target="_blank" class="text-green-700 underline">http://ec.europa.eu/consumers/odr/</a>
                </p>

                <h3 style="font-size:1.4rem; font-weight:600; color:#444; margin:1.5rem 0 1rem;">Derechos de propiedad intelectual e industrial</h3>
                <p>
                    En virtud de lo dispuesto en los artículos 8 y 32.1, párrafo segundo, de la Ley de Propiedad Intelectual, quedan expresamente prohibidas la reproducción, la distribución y la comunicación pública, incluida su modalidad de puesta a disposición, de la totalidad o parte de los contenidos de esta página web, con fines comerciales, en cualquier soporte y por cualquier medio técnico, sin la autorización de BIOESPACIO BIENESTAR SL. El usuario se compromete a respetar los derechos de Propiedad Intelectual e Industrial titularidad de BIOESPACIO BIENESTAR SL.
                    <br>
                    El usuario conoce y acepta que la totalidad del sitio web, conteniendo sin carácter exhaustivo el texto, software, contenidos (incluyendo estructura, selección, ordenación y presentación de los mismos) podcast, fotografías, material audiovisual y gráficos, está protegida por marcas, derechos de autor y otros derechos legítimos, de acuerdo con los tratados internacionales en los que España es parte y otros derechos de propiedad y leyes de España. En el caso de que un usuario o un tercero consideren que se ha producido una violación de sus legítimos derechos de propiedad intelectual por la introducción de un determinado contenido en la web, deberá notificar dicha circunstancia a BIOESPACIO BIENESTAR SL indicando:
                    <ul style="list-style-type:disc; padding-left:20px;">
                        <li>Datos personales del interesado titular de los derechos presuntamente infringidos, o indicar la representación con la que actúa en caso de que la reclamación la presente un tercero distinto del interesado.</li>
                        <li>Señalar los contenidos protegidos por los derechos de propiedad intelectual y su ubicación en la web, la acreditación de los derechos de propiedad intelectual señalados y declaración expresa en la que el interesado se responsabiliza de la veracidad de las informaciones facilitadas en la notificación.</li>
                    </ul>
                </p>

                <h3 style="font-size:1.4rem; font-weight:600; color:#444; margin:1.5rem 0 1rem;">Enlaces externos</h3>
                <p>
                    Las páginas de la web podrían proporcionar enlaces a otros sitios web propios y contenidos que son propiedad de terceros. El único objeto de los enlaces es proporcionar al Usuario la posibilidad de acceder a dichos enlaces. BIOESPACIO BIENESTAR SL no se responsabiliza en ningún caso de los resultados que puedan derivarse al Usuario por acceso a dichos enlaces.
                    <br>
                    Asimismo, el usuario encontrará dentro de este sitio, páginas, promociones, programas de afiliados que acceden a los hábitos de navegación de los usuarios para establecer perfiles. Esta información siempre es anónima y no se identifica al usuario.
                    <br>
                    La Información que se proporcione en estos Sitios patrocinado o enlaces de afiliados está sujeta a las políticas de privacidad que se utilicen en dichos Sitios y no estará sujeta a esta política de privacidad. Por lo que recomendamos ampliamente a los Usuarios a revisar detalladamente las políticas de privacidad de los enlaces de afiliado.
                    <br>
                    El Usuario que se proponga establecer cualquier dispositivo técnico de enlace desde su sitio web al portal deberá obtener la autorización previa y escrita de BIOESPACIO BIENESTAR SL. El establecimiento del enlace no implica en ningún caso la existencia de relaciones entre BIOESPACIO BIENESTAR SL y el propietario del sitio en el que se establezca el enlace, ni la aceptación o aprobación por parte de BIOESPACIO BIENESTAR SL de sus contenidos o servicios.
                </p>

                <h3 style="font-size:1.4rem; font-weight:600; color:#444; margin:1.5rem 0 1rem;">Política de comentarios</h3>
                <p>
                    En nuestra web se permiten realizar comentarios para enriquecer los contenidos y realizar consultas. No se admitirán comentarios que no estén relacionados con la temática de esta web, que incluyan difamaciones, agravios, insultos, ataques personales o faltas de respeto en general hacia el autor o hacia otros miembros. También serán suprimidos los comentarios que contengan información que sea obviamente engañosa o falsa, así como los comentarios que contengan información personal, como, por ejemplo, domicilios privado o teléfonos y que vulneren nuestra política de protección de datos.
                    <br>
                    Se desestimará, igualmente, aquellos comentarios creados sólo con fines promocionales de una web, persona o colectivo y todo lo que pueda ser considerado spam en general.
                    <br>
                    No se permiten comentarios anónimos, así como aquellos realizados por una misma persona con distintos apodos. No se considerarán tampoco aquellos comentarios que intenten forzar un debate o una toma de postura por otro usuario.
                </p>

                <h3 style="font-size:1.4rem; font-weight:600; color:#444; margin:1.5rem 0 1rem;">Exclusión de garantías y responsabilidad</h3>
                <p>
                    El Prestador no otorga ninguna garantía ni se hace responsable, en ningún caso, de los daños y perjuicios de cualquier naturaleza que pudieran traer causa de:
                    <ul style="list-style-type:disc; padding-left:20px;">
                        <li>La falta de disponibilidad, mantenimiento y efectivo funcionamiento de la web, o de sus servicios y contenidos;</li>
                        <li>La existencia de virus, programas maliciosos o lesivos en los contenidos;</li>
                        <li>El uso ilícito, negligente, fraudulento o contrario a este Aviso Legal;</li>
                        <li>La falta de licitud, calidad, fiabilidad, utilidad y disponibilidad de los servicios prestados por terceros y puestos a disposición de los usuarios en el sitio web.</li>
                        <li>El prestador no se hace responsable bajo ningún concepto de los daños que pudieran dimanar del uso ilegal o indebido de la presente página web.</li>
                    </ul>
                </p>

                <h3 style="font-size:1.4rem; font-weight:600; color:#444; margin:1.5rem 0 1rem;">Ley aplicable y jurisdicción</h3>
                <p>
                    Con carácter general las relaciones entre BIOESPACIO BIENESTAR SL con los Usuarios de sus servicios telemáticos, presentes en esta web se encuentran sometidas a la legislación y jurisdicción españolas y a los tribunales.
                </p>

                <h3 style="font-size:1.4rem; font-weight:600; color:#444; margin:1.5rem 0 1rem;">Contacto</h3>
                <p>
                    En caso de que cualquier Usuario tuviese alguna duda acerca de estas Condiciones legales o cualquier comentario sobre el portal, por favor diríjase a <a href="mailto:bioespaciobienestar@gmail.com" class="text-green-700 underline">bioespaciobienestar@gmail.com</a>
                    <br>
                    De parte del equipo que formamos BIOESPACIO BIENESTAR SL te agradecemos el tiempo dedicado en leer este Aviso Legal.
                </p>
            </div>
            <div style="border-top:2px solid #eee; margin-top:2rem; padding-top:1.5rem;">
                <div style="display:flex; gap:1.5rem; justify-content:center; margin-bottom:1.5rem;">
                    <button id="accept-cookies" style="${estilosBotonAceptar}">
                        Aceptar cookies
                    </button>
                    <button id="reject-cookies" style="${estilosBotonRechazar}">
                        Rechazar cookies
                    </button>
                </div>
                <button id="back-button" style="${estilosBotonVolver}">
                    ← Volver al inicio
                </button>
            </div>
        </div>
    `;

    function configurarEventListeners() {
        document.getElementById('show-cookies').onclick = function(e) {
            e.preventDefault();
            document.querySelector('#cookie-overlay').innerHTML = contenidoCookies;
            configurarTodosBotones();
        };

        document.getElementById('show-legal').onclick = function(e) {
            e.preventDefault();
            document.querySelector('#cookie-overlay').innerHTML = contenidoLegal;
            configurarTodosBotones();
        };

        configurarTodosBotones();
    }

    function configurarTodosBotones() {
        // Botón Aceptar
        document.getElementById('accept-cookies').onclick = function() {
            establecerCookie('cookies_accepted', '1', 30);
            document.getElementById('cookie-overlay').remove();
            document.body.style.overflow = '';
        };

        // Botón Rechazar
        document.getElementById('reject-cookies').onclick = function() {
            window.location.href = 'https://www.google.com';
        };

        // Botón Volver (si existe)
        const botonVolver = document.getElementById('back-button');
        if (botonVolver) {
            botonVolver.onclick = function() {
                document.querySelector('#cookie-overlay').innerHTML = contenidoInicial;
                configurarEventListeners();
            };
        }
    }

    configurarEventListeners();
}

// Función para establecer la cookie con duración en días
function establecerCookie(nombre, valor, dias) {
    let expira = '';
    if (dias) {
        const fecha = new Date();
        fecha.setTime(fecha.getTime() + (dias * 24 * 60 * 60 * 1000));
        expira = '; expires=' + fecha.toUTCString();
    }
    document.cookie = nombre + '=' + valor + expira + '; path=/';
}

// Función para obtener la cookie
function obtenerCookie(nombre) {
    const v = document.cookie.match('(^|;) ?' + nombre + '=([^;]*)(;|$)');
    return v ? v[2] : null;
}

// Mostrar overlay si no se han aceptado las cookies
if (!obtenerCookie('cookies_accepted')) {
    crearOverlayCookies();
    document.body.style.overflow = 'auto';
} else {
    document.body.style.overflow = '';
}
