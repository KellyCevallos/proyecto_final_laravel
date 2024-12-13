<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;



class Users extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'student']);
        Role::create(['name' => 'teacher']);
        DB::table('users')->insert([
            ['userName' => 'admin', 'password' => bcrypt('admin'), 'userNombres' => 'admin', 'userApellidos' => 'admin', 'userCorreo' => '', 'userWordKey' => 'admin'],
        ]);
        $user = DB::table('users')->insertGetId([
            'userName' => 'Jzamora', 
            'password' => bcrypt('123456'), 
            'userNombres' => 'Junior', 
            'userApellidos' => 'Zamora', 
            'userCorreo' => '', 
            'userWordKey' => 'rawr'
        ], 'userId');

        $userModel = \App\Models\User::find($user);
        $userModel->assignRole('teacher');
        DB::table('pagoType')->insert([
            ['pagoTypeId' => 1, 'pagoTypeName' => 'Tarjeta'],
            ['pagoTypeId' => 2, 'pagoTypeName' => 'Efectivo'],
            ['pagoTypeId' => 3, 'pagoTypeName' => 'Transferencia'],
            ['pagoTypeId' => 4, 'pagoTypeName' => 'Cheque'],
        ]);
        DB::table('nivel')->insert([
            ['nivelId' => 1, 'nivelName' => 'Basico'],
            ['nivelId' => 2, 'nivelName' => 'Intermedio'],
            ['nivelId' => 3, 'nivelName' => 'Avanzado'],
            ['nivelId' => 4, 'nivelName' => 'Profesional'],
        ]);
        DB::table('categorias')->insert([
            [
                'categoriaId' => 1, 
                'categoriaName' => 'Programación',
                'categoriaDescripcion' => 'Explora el fascinante mundo de la informática, adquiriendo habilidades para desarrollar algoritmos, aprender lenguajes de programación y resolver problemas con tecnología.',
                'categoriaImagen' => 'https://latam.emeritus.org/wp-content/uploads/sites/6/2024/08/programming-background-with-person-working-with-codes-computer-1024x683.jpg.optimal.jpg'
            ],
            [
                'categoriaId' => 2, 
                'categoriaName' => 'Arte',
                'categoriaDescripcion' => 'Descubre diversas expresiones artísticas, desde la pintura y la escultura hasta el diseño digital y la ilustración contemporánea.',
                'categoriaImagen' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Creaci%C3%B3n_de_Ad%C3%A1n.jpg/1200px-Creaci%C3%B3n_de_Ad%C3%A1n.jpg'
            ],
            [
                'categoriaId' => 3, 
                'categoriaName' => 'Marketing',
                'categoriaDescripcion' => 'Domina las estrategias modernas de marketing digital, branding, publicidad y ventas para destacar en un mercado competitivo.',
                'categoriaImagen' => 'https://www.fide.edu.pe/wp-content/uploads/2021/06/Diferencias-entre-Publicidad-Ventas-y-Marketing.jpg'
            ],
            [
                'categoriaId' => 4, 
                'categoriaName' => 'Negocios',
                'categoriaDescripcion' => 'Aprende a emprender y gestionar empresas, desarrollando habilidades en administración, finanzas y modelos de negocio exitosos.',
                'categoriaImagen' => 'https://tecnosoluciones.com/wp-content/uploads/2023/04/modelos-de-negocios-para-crear-una-empresa.png'
            ],
            [
                'categoriaId' => 5, 
                'categoriaName' => 'Idiomas',
                'categoriaDescripcion' => 'Sumérgete en el aprendizaje de nuevos idiomas y culturas, mejorando tus habilidades comunicativas y competencias globales.',
                'categoriaImagen' => 'https://itequia.com/wp-content/uploads/2023/11/Discrepancias-linguisticas-en-Internet-Los-idiomas-dominantes-scaled.jpg'
            ],
            [
                'categoriaId' => 6, 
                'categoriaName' => 'Cocina',
                'categoriaDescripcion' => 'Aprende técnicas culinarias, prepara recetas internacionales y descubre los secretos de la alta gastronomía.',
                'categoriaImagen' => 'https://www.larepublica.net/storage/images/2018/08/01/20180801153436.gastropinion.jpg?mrf-size=m'
            ],
            [
                'categoriaId' => 7, 
                'categoriaName' => 'Música',
                'categoriaDescripcion' => 'Explora la teoría musical, aprende a tocar instrumentos y sumérgete en diferentes géneros y estilos de música.',
                'categoriaImagen' => 'https://i0.wp.com/musicalidades.com.br/wp-content/uploads/2018/11/o-que-e-musica-4.jpg?fit=1080%2C512&ssl=1'
            ],
            [
                'categoriaId' => 8, 
                'categoriaName' => 'Deportes',
                'categoriaDescripcion' => 'Mejora tu condición física y descubre disciplinas deportivas que fomentan el bienestar y la salud.',
                'categoriaImagen' => 'https://png.pngtree.com/thumb_back/fw800/background/20230910/pngtree-most-used-sports-equipment-in-new-hampshire-image_13169642.png'
            ],
            [
                'categoriaId' => 9, 
                'categoriaName' => 'Medicina',
                'categoriaDescripcion' => 'Adquiere conocimientos sobre salud, anatomía y tratamientos médicos, contribuyendo al bienestar de la comunidad.',
                'categoriaImagen' => 'https://impulso06.com/wp-content/uploads/2023/09/Formacion-continua-en-salud-importancia-y-ventajas.png'
            ],
            [
                'categoriaId' => 10, 
                'categoriaName' => 'Física',
                'categoriaDescripcion' => 'Comprende los fundamentos de la física y las matemáticas, explorando conceptos desde la mecánica hasta la teoría cuántica.',
                'categoriaImagen' => 'https://www.emagister.com/blog/wp-content/uploads/2021/11/GettyImages-936903524.jpg'
            ],
        ]);
        DB::table('cursos')->insert([
            'cursoName' => 'Fundamentos de Programación',
            'cursoDescripcion' => 'Este curso cubre los fundamentos de la programación, abordando desde la creación de variables hasta estructuras más complejas como bucles y manejo de errores.',
            'cursoNivelId' => 1, // Nivel Básico
            'cursoValor' => 150, // Valor en dólares
            'cursoRequisito' => 'Conocimientos básicos en informática.',
            'cursoContenido' => json_encode([
                [
                    'titulo' => 'Introducción a la Programación',
                    'media' => 'https://www.introduccionalaprogramacion.com.ar/img/introduccion-programacion.png',
                    'concepto' => 'La programación es el proceso de creación de instrucciones para que las computadoras realicen tareas específicas. Existen varios lenguajes de programación como Python, Java, y C++, cada uno con un propósito particular. La programación estructurada organiza el código en secuencias claras y la programación orientada a objetos trabaja con clases y objetos que representan entidades del mundo real.'
                ],
                [
                    'titulo' => 'Variables y Tipos de Datos',
                    'media' => 'https://i0.wp.com/rduinostar.com/wp-content/uploads/2012/10/Tipos-de-Variables-Arduino.jpg',
                    'concepto' => 'Una variable es un contenedor que almacena un valor temporalmente. Existen distintos tipos de datos: 
                    - **Entero (int)**: Números enteros (ej. 10, -5). 
                    - **Flotante (float)**: Números con decimales (ej. 3.14).
                    - **Cadena (string)**: Texto (ej. "Hola Mundo").
                    - **Booleano (bool)**: Verdadero o falso (ej. true, false). 
                    Las buenas prácticas sugieren usar nombres descriptivos para las variables, evitando confusiones.'
                ],
                [
                    'titulo' => 'Estructuras de Control',
                    'media' => 'https://procomsys.wordpress.com/wp-content/uploads/2018/05/escon.png',
                    'concepto' => 'Las estructuras de control permiten que el flujo del programa se adapte a condiciones específicas. 
                    - **Condicionales (if-else)**: Ejecutan diferentes bloques de código según una condición (ej. Si la edad es mayor a 18, mostrar "Adulto"). 
                    - **Bucles (for, while)**: Permiten repetir un bloque de código varias veces. El bucle **for** se usa cuando se conoce la cantidad de iteraciones; **while** se usa cuando la condición es indefinida.'
                ],
                [
                    'titulo' => 'Funciones y Procedimientos',
                    'media' => 'https://youtu.be/Oqqxfr8WNlQ?si=6KTl4teOEUKDZqVy',
                    'concepto' => 'Una función es un bloque de código reutilizable que realiza una tarea específica. Se define con un nombre, puede recibir parámetros y retornar un valor. 
                    - **Función con retorno**: Devuelve un resultado (ej. sumar(5, 3) devuelve 8). 
                    - **Procedimiento**: Similar a una función, pero no retorna un valor. Las funciones ayudan a organizar el código y evitar redundancias.'
                ],
                [
                    'titulo' => 'Manejo de Errores y Depuración',
                    'media' => 'https://images.datacamp.com/image/upload/v1677232088/Exception%20and%20error%20handling%20in%20Python.png',
                    'concepto' => 'El manejo de errores es crucial para asegurar que un programa funcione correctamente incluso ante fallos. 
                    - **Excepciones**: Permiten capturar y manejar errores (ej. dividir por 0). 
                    - **Depuración**: Es el proceso de identificar y corregir errores en el código utilizando herramientas específicas. La prevención de errores mejora la estabilidad del software.'
                ]
            ]),
            'cursoExamen' => json_encode([
                [
                    'pregunta' => '¿Qué es una variable?',
                    'opciones' => [
                        'Un tipo de dato', 
                        'Un contenedor para almacenar datos', 
                        'Una función que repite acciones', 
                        'Un error en el código'
                    ],
                    'respuestaCorrecta' => 'Un contenedor para almacenar datos'
                ],
                [
                    'pregunta' => '¿Qué tipo de dato usarías para almacenar el número 3.14?',
                    'opciones' => ['int', 'float', 'string', 'bool'],
                    'respuestaCorrecta' => 'float'
                ],
                [
                    'pregunta' => '¿Cuál es la función principal de una estructura "if-else"?',
                    'opciones' => [
                        'Repetir un bloque de código', 
                        'Tomar decisiones basadas en condiciones', 
                        'Ordenar datos en una lista', 
                        'Almacenar datos de forma temporal'
                    ],
                    'respuestaCorrecta' => 'Tomar decisiones basadas en condiciones'
                ],
                [
                    'pregunta' => '¿Qué herramienta se usa para identificar errores en un programa?',
                    'opciones' => ['Editor de texto', 'Depurador', 'Sistema operativo', 'Bucle'],
                    'respuestaCorrecta' => 'Depurador'
                ],
                [
                    'pregunta' => '¿Qué diferencia a una función de un procedimiento?',
                    'opciones' => [
                        'La función retorna un valor y el procedimiento no', 
                        'Ambos retornan valores', 
                        'El procedimiento recibe parámetros y la función no', 
                        'El procedimiento se usa solo para errores'
                    ],
                    'respuestaCorrecta' => 'La función retorna un valor y el procedimiento no'
                ],
                [
                    'pregunta' => '¿Qué estructura repetiría un bloque de código 10 veces?',
                    'opciones' => ['if-else', 'for', 'while', 'switch'],
                    'respuestaCorrecta' => 'for'
                ],
                [
                    'pregunta' => '¿Qué tipo de dato se usaría para almacenar "true"?',
                    'opciones' => ['int', 'float', 'string', 'bool'],
                    'respuestaCorrecta' => 'bool'
                ]
            ]),
            'createdBy' => 'Junior Zamora',
            'cursoCategoriaId' => 1, // Programación
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('cursos')->insert([
            'cursoName' => 'Historia del Arte Clásico',
            'cursoDescripcion' => 'Explora el arte desde las civilizaciones antiguas hasta el Renacimiento, analizando su evolución y su impacto cultural.',
            'cursoNivelId' => 2, // Intermedio
            'cursoValor' => 200,
            'cursoRequisito' => 'Interés en el arte y la historia.',
            'cursoContenido' => json_encode([
                [
                    'titulo' => 'Arte Griego: Búsqueda de la Belleza Perfecta',
                    'media' => 'https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhcGg4lgiGqcVJ0EwJaEojuAZs7zppst_hRgT6ZTalB2QcAjobHpCzXCYChE1pH9PPd1KWDcmlr5zAKBng_T1IVq_nowQVy3DZ0aw_AZApYPSbkfbTRhHdu0y4H8qY5F_LEVEWbcc_pNz8/s1600/Dor%25C3%25ADforo.jpg',
                    'concepto' => 'El arte griego busca la perfección estética, enfatizando la proporción en la escultura y la arquitectura. Las estatuas como "El Discóbolo" de Mirón y los templos como el Partenón simbolizan esta búsqueda del equilibrio ideal entre forma y función.'
                ],
                [
                    'titulo' => 'Arte Romano: Funcionalidad y Propaganda',
                    'media' => 'https://st2.depositphotos.com/1834167/7270/i/450/depositphotos_72700697-stock-photo-the-moses-by-michelangelo-rome.jpg',
                    'concepto' => 'El arte romano combina la funcionalidad con la propaganda política. Los monumentos como los arcos triunfales celebran victorias militares, mientras que los acueductos y teatros demuestran el avance en ingeniería para servir a la sociedad.'
                ],
                [
                    'titulo' => 'Arte Medieval: Espiritualidad y Simbolismo',
                    'media' => 'https://educacioncontinua.uniandes.edu.co/sites/default/files/images_salesforce/Cursos/a0g1O00000Bzk1NQAR/curso_de_arte_medieval_educacion_continua_uniandes_curso_de_apreciacion_del_arte_arte_entender_el_arte_arte_antiguo_taller_en_arte_banner_movil.jpg',
                    'concepto' => 'El arte medieval prioriza la espiritualidad sobre el realismo. Las iglesias románicas y góticas contienen frescos y vitrales que enseñan temas religiosos a una audiencia en su mayoría analfabeta.'
                ],
                [
                    'titulo' => 'Renacimiento: El Retorno a la Antigüedad Clásica',
                    'media' => 'https://i0.wp.com/diazvillanueva.com/wp-content/uploads/2020/10/Escuela-Atenas-rafael.jpg?resize=678%2C381&ssl=1',
                    'concepto' => 'El Renacimiento marca una vuelta a la apreciación del arte y la ciencia de la antigüedad clásica. Artistas como Miguel Ángel y Rafael perfeccionaron la perspectiva y el estudio anatómico para lograr un realismo sin precedentes.'
                ],
                [
                    'titulo' => 'El Legado del Arte Clásico en la Cultura Moderna',
                    'media' => 'https://www.youtube.com/watch?v=vt6CcEthRHw',
                    'concepto' => 'El arte clásico sigue influyendo en el diseño contemporáneo, desde la arquitectura moderna hasta el cine y la publicidad, demostrando la perdurabilidad de estos conceptos estéticos.'
                ]
            ]),
            'cursoExamen' => json_encode([
                [
                    'pregunta' => '¿Qué ideal perseguía el arte griego?',
                    'opciones' => ['Funcionalidad', 'Simbología', 'Proporción y belleza', 'Abstracción'],
                    'respuestaCorrecta' => 'Proporción y belleza'
                ],
                [
                    'pregunta' => '¿Qué uso le dieron los romanos al arte?',
                    'opciones' => ['Solo decoración', 'Propaganda política', 'Entretenimiento', 'Magia'],
                    'respuestaCorrecta' => 'Propaganda política'
                ],
                [
                    'pregunta' => '¿Cuál es una característica del arte medieval?',
                    'opciones' => ['Realismo anatómico', 'Abstracción geométrica', 'Simbología religiosa', 'Uso de perspectiva lineal'],
                    'respuestaCorrecta' => 'Simbología religiosa'
                ],
                [
                    'pregunta' => '¿Qué es una perspectiva en arte?',
                    'opciones' => [
                        'Una técnica matemática', 
                        'Una herramienta para representar profundidad', 
                        'Un tipo de escultura', 
                        'Una disciplina artística'
                    ],
                    'respuestaCorrecta' => 'Una herramienta para representar profundidad'
                ],
                [
                    'pregunta' => '¿Qué es un fresco?',
                    'opciones' => ['Pintura sobre tela', 'Escultura en mármol', 'Pintura sobre yeso húmedo', 'Vidrio pintado'],
                    'respuestaCorrecta' => 'Pintura sobre yeso húmedo'
                ]
            ]),
            'createdBy' => 'Junior Zamora',
            'cursoCategoriaId' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('cursos')->insert([
            'cursoName' => 'Introducción al Arte Digital',
            'cursoDescripcion' => 'Domina las herramientas y técnicas esenciales para la creación de arte digital, desde ilustración hasta animación.',
            'cursoNivelId' => 1, // Básico
            'cursoValor' => 180,
            'cursoRequisito' => 'Conocimientos básicos en informática.',
            'cursoContenido' => json_encode([
                [
                    'titulo' => 'Herramientas Digitales para la Creación de Arte',
                    'media' => '',
                    'concepto' => 'Photoshop y Procreate son herramientas fundamentales para el arte digital. Photoshop es ideal para retoque fotográfico y creación de composiciones complejas, mientras que Procreate permite ilustraciones precisas en dispositivos móviles.'
                ],
                [
                    'titulo' => 'Ilustración Digital: Conceptos Básicos',
                    'media' => '',
                    'concepto' => 'La ilustración digital permite crear dibujos utilizando pinceles personalizados. Las capas ayudan a organizar el trabajo, separando fondo, personajes y detalles, lo que facilita la edición y mejora la precisión del resultado final.'
                ],
                [
                    'titulo' => 'Principios del Diseño Gráfico en el Arte Digital',
                    'media' => '',
                    'concepto' => 'El diseño gráfico combina elementos como texto, imágenes y colores para comunicar mensajes. En el arte digital, los principios como alineación, contraste y jerarquía visual garantizan que las obras sean atractivas y comprensibles.'
                ],
                [
                    'titulo' => 'Fundamentos de la Animación 2D y 3D',
                    'media' => '',
                    'concepto' => 'La animación 2D se basa en la creación de cuadros consecutivos para simular movimiento, mientras que en la animación 3D se utilizan modelos tridimensionales que se animan en espacios digitales, como en películas y videojuegos.'
                ],
                [
                    'titulo' => 'Creación de un Portafolio Digital Atractivo',
                    'media' => '',
                    'concepto' => 'Un portafolio digital es esencial para mostrar las habilidades del artista. Organizar las mejores obras en plataformas como Behance permite destacar en la industria y atraer clientes o empleadores.'
                ]
            ]),
            'cursoExamen' => json_encode([
                [
                    'pregunta' => '¿Cuál es una herramienta para retoque fotográfico?',
                    'opciones' => ['Procreate', 'Photoshop', 'AutoCAD', 'Word'],
                    'respuestaCorrecta' => 'Photoshop'
                ],
                [
                    'pregunta' => '¿Qué es una capa en la ilustración digital?',
                    'opciones' => [
                        'Una herramienta para dibujar líneas', 
                        'Una técnica de color', 
                        'Un elemento que organiza el dibujo', 
                        'Un tipo de pincel'
                    ],
                    'respuestaCorrecta' => 'Un elemento que organiza el dibujo'
                ],
                [
                    'pregunta' => '¿Qué principio garantiza que una obra digital sea atractiva?',
                    'opciones' => ['Alineación', 'Aleatoriedad', 'Simetría exacta', 'Uso de colores oscuros'],
                    'respuestaCorrecta' => 'Alineación'
                ],
                [
                    'pregunta' => '¿Cuál es la diferencia principal entre animación 2D y 3D?',
                    'opciones' => [
                        'La animación 2D es más realista', 
                        'La animación 3D usa modelos tridimensionales', 
                        'La animación 3D no se utiliza en videojuegos', 
                        'Ambas técnicas son iguales'
                    ],
                    'respuestaCorrecta' => 'La animación 3D usa modelos tridimensionales'
                ],
                [
                    'pregunta' => '¿Dónde se recomienda publicar un portafolio digital?',
                    'opciones' => ['Instagram', 'Behance', 'Wikipedia', 'Google Drive'],
                    'respuestaCorrecta' => 'Behance'
                ]
            ]),
            'createdBy' => 'Junior Zamora',
            'cursoCategoriaId' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('cursos')->insert([
            'cursoName' => 'Fundamentos del Marketing Digital',
            'cursoDescripcion' => 'Aprende los conceptos esenciales del marketing digital, incluyendo SEO, redes sociales y campañas pagadas.',
            'cursoNivelId' => 1, // Básico
            'cursoValor' => 250,
            'cursoRequisito' => 'Conocimientos básicos de navegación en internet.',
            'cursoContenido' => json_encode([
                [
                    'titulo' => 'SEO: Posicionamiento Orgánico en Buscadores',
                    'media' => '',
                    'concepto' => 'El SEO (Search Engine Optimization) se enfoca en optimizar el contenido de una web para mejorar su posición en los resultados de búsqueda orgánicos. Se trabaja en aspectos técnicos como palabras clave, etiquetas meta y backlinks para aumentar la visibilidad sin pagar por anuncios.'
                ],
                [
                    'titulo' => 'Marketing en Redes Sociales',
                    'media' => '',
                    'concepto' => 'Las redes sociales son plataformas clave para conectar con la audiencia. Cada red tiene un enfoque particular: Instagram se centra en imágenes, Twitter en información rápida y LinkedIn en el ámbito profesional. Una estrategia sólida requiere contenido relevante y publicación constante.'
                ],
                [
                    'titulo' => 'Campañas de Publicidad Pagada (SEM)',
                    'media' => '',
                    'concepto' => 'El SEM (Search Engine Marketing) utiliza anuncios pagados en buscadores como Google para atraer tráfico inmediato. Estas campañas funcionan mediante pujas por palabras clave y la creación de anuncios atractivos. El seguimiento del rendimiento es fundamental para optimizar el retorno de inversión.'
                ],
                [
                    'titulo' => 'Email Marketing: Comunicación Directa con Clientes',
                    'media' => '',
                    'concepto' => 'El email marketing es una herramienta poderosa para fidelizar clientes. Consiste en enviar correos electrónicos personalizados con ofertas, noticias o contenido exclusivo. La segmentación de la base de datos es esencial para mejorar la relevancia de los mensajes.'
                ],
                [
                    'titulo' => 'Analítica Web y Métricas',
                    'media' => '',
                    'concepto' => 'La analítica web permite medir el impacto de las estrategias de marketing. Herramientas como Google Analytics proporcionan datos sobre el comportamiento de los usuarios, permitiendo ajustar campañas para mejorar resultados.'
                ]
            ]),
            'cursoExamen' => json_encode([
                [
                    'pregunta' => '¿Qué es SEO?',
                    'opciones' => [
                        'Un tipo de campaña pagada', 
                        'Una técnica para mejorar la visibilidad orgánica en buscadores', 
                        'Una red social', 
                        'Un formato de publicidad en video'
                    ],
                    'respuestaCorrecta' => 'Una técnica para mejorar la visibilidad orgánica en buscadores'
                ],
                [
                    'pregunta' => '¿Qué red social es ideal para contenido profesional?',
                    'opciones' => ['Instagram', 'LinkedIn', 'TikTok', 'Snapchat'],
                    'respuestaCorrecta' => 'LinkedIn'
                ],
                [
                    'pregunta' => '¿Qué significa SEM?',
                    'opciones' => [
                        'Social Engagement Marketing', 
                        'Search Engine Marketing', 
                        'Sales and E-commerce Marketing', 
                        'Search Engine Management'
                    ],
                    'respuestaCorrecta' => 'Search Engine Marketing'
                ],
                [
                    'pregunta' => '¿Cuál es una clave del email marketing efectivo?',
                    'opciones' => ['Enviar correos masivos', 'Segmentar la audiencia', 'Usar emojis en todos los correos', 'Evitar el seguimiento de resultados'],
                    'respuestaCorrecta' => 'Segmentar la audiencia'
                ],
                [
                    'pregunta' => '¿Qué herramienta se usa para medir el tráfico web?',
                    'opciones' => ['Hootsuite', 'Google Analytics', 'Canva', 'Google Ads'],
                    'respuestaCorrecta' => 'Google Analytics'
                ]
            ]),
            'createdBy' => 'Junior Zamora',
            'cursoCategoriaId' => 3, // Marketing
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('cursos')->insert([
            'cursoName' => 'Estrategias de Branding y Posicionamiento',
            'cursoDescripcion' => 'Aprende a construir marcas sólidas y posicionarlas en la mente del consumidor para lograr diferenciación.',
            'cursoNivelId' => 2, // Intermedio
            'cursoValor' => 300,
            'cursoRequisito' => 'Conocimiento básico de marketing.',
            'cursoContenido' => json_encode([
                [
                    'titulo' => 'Construcción de la Identidad de Marca',
                    'media' => '',
                    'concepto' => 'La identidad de marca incluye elementos visuales como el logo, colores y tipografía, pero también valores y personalidad que la diferencian. Una identidad coherente genera reconocimiento y confianza en los consumidores.'
                ],
                [
                    'titulo' => 'El Posicionamiento en la Mente del Consumidor',
                    'media' => '',
                    'concepto' => 'El posicionamiento busca ocupar un lugar claro y preferente en la mente del consumidor. Se logra comunicando de manera consistente los beneficios y la propuesta de valor de la marca, comparándola con la competencia.'
                ],
                [
                    'titulo' => 'Estrategias de Diferenciación',
                    'media' => '',
                    'concepto' => 'La diferenciación se enfoca en resaltar características únicas que hagan a la marca inconfundible. Puede lograrse mediante innovación, calidad superior, atención al cliente, o propuestas ecológicas.'
                ],
                [
                    'titulo' => 'Branding Emocional y Experiencia del Cliente',
                    'media' => '',
                    'concepto' => 'El branding emocional busca conectar con las emociones del consumidor, creando una experiencia memorable. La experiencia del cliente incluye todos los puntos de contacto con la marca, desde la atención en tienda hasta el servicio postventa.'
                ],
                [
                    'titulo' => 'Gestión de la Reputación de Marca',
                    'media' => '',
                    'concepto' => 'La reputación de marca se construye a lo largo del tiempo mediante la consistencia y el cumplimiento de promesas. Hoy en día, las redes sociales juegan un papel crucial en la gestión de la reputación.'
                ]
            ]),
            'cursoExamen' => json_encode([
                [
                    'pregunta' => '¿Qué es la identidad de marca?',
                    'opciones' => [
                        'Solo el logo de la empresa', 
                        'La combinación de elementos visuales, valores y personalidad', 
                        'Una estrategia de ventas', 
                        'El registro de la marca en la ley'
                    ],
                    'respuestaCorrecta' => 'La combinación de elementos visuales, valores y personalidad'
                ],
                [
                    'pregunta' => '¿Qué busca el posicionamiento de marca?',
                    'opciones' => [
                        'Aumentar las ventas inmediatas', 
                        'Ocupar un lugar claro en la mente del consumidor', 
                        'Ser la marca más costosa', 
                        'Reducir la competencia'
                    ],
                    'respuestaCorrecta' => 'Ocupar un lugar claro en la mente del consumidor'
                ],
                [
                    'pregunta' => '¿Cuál es una estrategia de diferenciación?',
                    'opciones' => ['Innovación', 'Copiar a la competencia', 'Reducir precios', 'Publicidad en radio'],
                    'respuestaCorrecta' => 'Innovación'
                ],
                [
                    'pregunta' => '¿Qué busca el branding emocional?',
                    'opciones' => [
                        'Conectar con las emociones del consumidor', 
                        'Aumentar el precio del producto', 
                        'Eliminar a la competencia', 
                        'Ofrecer descuentos masivos'
                    ],
                    'respuestaCorrecta' => 'Conectar con las emociones del consumidor'
                ],
                [
                    'pregunta' => '¿Por qué es importante la reputación de marca?',
                    'opciones' => [
                        'Para reducir costos', 
                        'Para cumplir las expectativas del cliente', 
                        'Para eliminar el branding emocional', 
                        'Para copiar las estrategias de otras empresas'
                    ],
                    'respuestaCorrecta' => 'Para cumplir las expectativas del cliente'
                ]
            ]),
            'createdBy' => 'Junior Zamora',
            'cursoCategoriaId' => 3, // Marketing
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('cursos')->insert([
            'cursoName' => 'Fundamentos de Emprendimiento',
            'cursoDescripcion' => 'Aprende los principios básicos para iniciar un negocio exitoso, desde la validación de ideas hasta la gestión del crecimiento.',
            'cursoNivelId' => 1, // Básico
            'cursoValor' => 300,
            'cursoRequisito' => 'Interés en iniciar un negocio.',
            'cursoContenido' => json_encode([
                [
                    'titulo' => 'Identificación de Oportunidades de Negocio',
                    'media' => '',
                    'concepto' => 'Las oportunidades de negocio surgen al identificar necesidades no satisfechas en el mercado. Es importante realizar investigaciones de mercado para validar la idea y analizar la competencia.'
                ],
                [
                    'titulo' => 'Elaboración de un Modelo de Negocio',
                    'media' => '',
                    'concepto' => 'El modelo de negocio define cómo una empresa genera valor y obtiene ingresos. Se utiliza el Business Model Canvas para estructurar los segmentos de clientes, propuesta de valor, canales de distribución y fuentes de ingresos.'
                ],
                [
                    'titulo' => 'Estrategias de Financiación',
                    'media' => '',
                    'concepto' => 'Existen varias opciones para financiar un negocio: capital propio, préstamos, inversores o crowdfunding. Es fundamental evaluar los riesgos de cada opción y buscar la más adecuada para la situación del negocio.'
                ],
                [
                    'titulo' => 'Gestión del Crecimiento Empresarial',
                    'media' => '',
                    'concepto' => 'El crecimiento de un negocio implica escalar operaciones y mantener una buena gestión financiera. La expansión puede hacerse mediante nuevos mercados, productos o colaboraciones estratégicas.'
                ],
                [
                    'titulo' => 'Gestión del Riesgo y Adaptación al Cambio',
                    'media' => '',
                    'concepto' => 'Los negocios enfrentan riesgos económicos, tecnológicos y sociales. La gestión del riesgo consiste en identificar amenazas y preparar estrategias para mitigar su impacto. Adaptarse rápidamente a los cambios del entorno es clave para la sostenibilidad.'
                ]
            ]),
            'cursoExamen' => json_encode([
                [
                    'pregunta' => '¿Qué es una oportunidad de negocio?',
                    'opciones' => [
                        'Una necesidad satisfecha en el mercado', 
                        'Una necesidad no satisfecha en el mercado', 
                        'Un tipo de préstamo', 
                        'Una alianza estratégica'
                    ],
                    'respuestaCorrecta' => 'Una necesidad no satisfecha en el mercado'
                ],
                [
                    'pregunta' => '¿Qué es el Business Model Canvas?',
                    'opciones' => [
                        'Un esquema para elaborar productos', 
                        'Una técnica de marketing', 
                        'Un modelo para estructurar negocios', 
                        'Una herramienta de gestión de riesgos'
                    ],
                    'respuestaCorrecta' => 'Un modelo para estructurar negocios'
                ],
                [
                    'pregunta' => '¿Qué es el crowdfunding?',
                    'opciones' => [
                        'Una técnica de ventas', 
                        'Una estrategia de expansión', 
                        'Una forma de financiación colectiva', 
                        'Una política de precios'
                    ],
                    'respuestaCorrecta' => 'Una forma de financiación colectiva'
                ],
                [
                    'pregunta' => '¿Qué significa escalar un negocio?',
                    'opciones' => [
                        'Reducir operaciones', 
                        'Aumentar operaciones manteniendo la eficiencia', 
                        'Cerrar sucursales', 
                        'Cambiar la estructura del negocio'
                    ],
                    'respuestaCorrecta' => 'Aumentar operaciones manteniendo la eficiencia'
                ],
                [
                    'pregunta' => '¿Por qué es importante la gestión del riesgo?',
                    'opciones' => [
                        'Para asegurar préstamos', 
                        'Para prevenir pérdidas significativas', 
                        'Para atraer más clientes', 
                        'Para eliminar la competencia'
                    ],
                    'respuestaCorrecta' => 'Para prevenir pérdidas significativas'
                ]
            ]),
            'createdBy' => 'Junior Zamora',
            'cursoCategoriaId' => 4, // Negocios
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('cursos')->insert([
            'cursoName' => 'Gestión Empresarial y Liderazgo',
            'cursoDescripcion' => 'Desarrolla habilidades de gestión empresarial y liderazgo para dirigir equipos y organizaciones exitosamente.',
            'cursoNivelId' => 2, // Intermedio
            'cursoValor' => 350,
            'cursoRequisito' => 'Experiencia básica en gestión de equipos.',
            'cursoContenido' => json_encode([
                [
                    'titulo' => 'Planeación Estratégica',
                    'media' => '',
                    'concepto' => 'La planeación estratégica define la dirección que debe seguir una organización a largo plazo. Implica el establecimiento de objetivos claros, análisis de entorno y definición de estrategias para alcanzar las metas propuestas.'
                ],
                [
                    'titulo' => 'Habilidades de Liderazgo',
                    'media' => '',
                    'concepto' => 'Un buen líder inspira y guía a su equipo hacia el cumplimiento de objetivos. Las habilidades esenciales incluyen comunicación efectiva, empatía, toma de decisiones y resolución de conflictos.'
                ],
                [
                    'titulo' => 'Gestión del Talento Humano',
                    'media' => '',
                    'concepto' => 'La gestión del talento implica atraer, desarrollar y retener al personal más calificado. También se enfoca en fomentar un ambiente de trabajo positivo y alinear los objetivos del personal con los de la organización.'
                ],
                [
                    'titulo' => 'Control Financiero y Presupuestos',
                    'media' => '',
                    'concepto' => 'La gestión financiera asegura que la empresa utilice eficientemente sus recursos. La elaboración de presupuestos permite controlar gastos y proyectar ingresos, garantizando la sostenibilidad económica del negocio.'
                ],
                [
                    'titulo' => 'Gestión del Cambio Organizacional',
                    'media' => '',
                    'concepto' => 'Las organizaciones deben adaptarse a los cambios en su entorno. La gestión del cambio ayuda a minimizar la resistencia del personal y asegurar una transición fluida hacia nuevas formas de trabajo.'
                ]
            ]),
            'cursoExamen' => json_encode([
                [
                    'pregunta' => '¿Qué es la planeación estratégica?',
                    'opciones' => [
                        'Una estrategia de marketing', 
                        'Un proceso para definir la dirección a largo plazo', 
                        'Una técnica para gestionar equipos', 
                        'Un método para asignar tareas'
                    ],
                    'respuestaCorrecta' => 'Un proceso para definir la dirección a largo plazo'
                ],
                [
                    'pregunta' => '¿Cuál es una habilidad clave de liderazgo?',
                    'opciones' => [
                        'Delegación efectiva', 
                        'Hacer todo el trabajo personalmente', 
                        'Evitar conflictos', 
                        'Ser autoritario'
                    ],
                    'respuestaCorrecta' => 'Delegación efectiva'
                ],
                [
                    'pregunta' => '¿Qué implica la gestión del talento humano?',
                    'opciones' => [
                        'Capacitar al personal y retenerlo', 
                        'Aumentar la carga de trabajo', 
                        'Pagar salarios más bajos', 
                        'Eliminar procesos de selección'
                    ],
                    'respuestaCorrecta' => 'Capacitar al personal y retenerlo'
                ],
                [
                    'pregunta' => '¿Qué es un presupuesto?',
                    'opciones' => [
                        'Una herramienta para controlar ingresos y gastos', 
                        'Un informe anual', 
                        'Un contrato de ventas', 
                        'Un manual de procedimientos'
                    ],
                    'respuestaCorrecta' => 'Una herramienta para controlar ingresos y gastos'
                ],
                [
                    'pregunta' => '¿Qué busca la gestión del cambio?',
                    'opciones' => [
                        'Minimizar la resistencia al cambio', 
                        'Evitar cualquier modificación', 
                        'Eliminar la toma de decisiones', 
                        'Reducir el tamaño del equipo'
                    ],
                    'respuestaCorrecta' => 'Minimizar la resistencia al cambio'
                ]
            ]),
            'createdBy' => 'Junior Zamora',
            'cursoCategoriaId' => 4, // Negocios
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('cursos')->insert([
            'cursoName' => 'Inglés para Principiantes',
            'cursoDescripcion' => 'Este curso está diseñado para quienes inician su aprendizaje del idioma inglés, cubriendo gramática básica, vocabulario y habilidades comunicativas esenciales.',
            'cursoNivelId' => 1, // Básico
            'cursoValor' => 150,
            'cursoRequisito' => 'Conocimientos mínimos de lectura en cualquier idioma.',
            'cursoContenido' => json_encode([
                [
                    'titulo' => 'El Alfabeto y la Pronunciación',
                    'media' => '',
                    'concepto' => 'El alfabeto inglés consta de 26 letras, y su pronunciación varía según la región. La fonética es esencial para reconocer cómo suenan las vocales y consonantes en diferentes contextos. Practicar el alfabeto facilita la pronunciación correcta de palabras.'
                ],
                [
                    'titulo' => 'Vocabulario Básico',
                    'media' => '',
                    'concepto' => 'El vocabulario básico incluye saludos como "Hello", "Good morning", y expresiones comunes como "Thank you" y "Please". Es fundamental aprender nombres de objetos cotidianos, colores y números para interactuar en situaciones simples.'
                ],
                [
                    'titulo' => 'Gramática Elemental: Verbos y Pronombres',
                    'media' => '',
                    'concepto' => 'Los verbos más comunes son "to be" (ser/estar) y "to have" (tener). Los pronombres personales (I, you, he, she, it, we, they) son esenciales para formar oraciones simples. La gramática básica permite construir frases afirmativas y negativas.'
                ],
                [
                    'titulo' => 'Conversaciones Cotidianas',
                    'media' => '',
                    'concepto' => 'Este bloque enseña cómo presentarse, preguntar por direcciones y realizar compras simples. Expresiones como "How are you?" y "How much does it cost?" son esenciales para situaciones del día a día.'
                ],
                [
                    'titulo' => 'Escucha y Comprensión',
                    'media' => '',
                    'concepto' => 'Escuchar es una habilidad clave en el aprendizaje de idiomas. Practicar con audios y conversaciones sencillas permite mejorar la comprensión de frases comunes y respuestas rápidas en inglés.'
                ]
            ]),
            'cursoExamen' => json_encode([
                [
                    'pregunta' => '¿Cuántas letras tiene el alfabeto inglés?',
                    'opciones' => ['24', '26', '28', '30'],
                    'respuestaCorrecta' => '26'
                ],
                [
                    'pregunta' => '¿Qué significa "Hello"?',
                    'opciones' => ['Hola', 'Adiós', 'Por favor', 'Gracias'],
                    'respuestaCorrecta' => 'Hola'
                ],
                [
                    'pregunta' => '¿Cuál es el pronombre para "ella"?',
                    'opciones' => ['He', 'She', 'They', 'It'],
                    'respuestaCorrecta' => 'She'
                ],
                [
                    'pregunta' => '¿Cómo se dice "gracias" en inglés?',
                    'opciones' => ['Please', 'Goodbye', 'Thanks', 'Thank you'],
                    'respuestaCorrecta' => 'Thank you'
                ],
                [
                    'pregunta' => '¿Cuál es el verbo "to be" en la primera persona singular?',
                    'opciones' => ['Are', 'Am', 'Is', 'Be'],
                    'respuestaCorrecta' => 'Am'
                ]
            ]),
            'createdBy' => 'Junior Zamora',
            'cursoCategoriaId' => 5, // Idiomas
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('cursos')->insert([
            'cursoName' => 'Español para Extranjeros',
            'cursoDescripcion' => 'Este curso está dirigido a hablantes no nativos que desean aprender español, cubriendo gramática básica, vocabulario y habilidades comunicativas.',
            'cursoNivelId' => 1, // Básico
            'cursoValor' => 200,
            'cursoRequisito' => 'Interés por aprender un nuevo idioma.',
            'cursoContenido' => json_encode([
                [
                    'titulo' => 'El Alfabeto y la Pronunciación',
                    'media' => '',
                    'concepto' => 'El español tiene 27 letras, incluyendo la "ñ". La pronunciación es clara y consistente, con pocas variaciones regionales. Aprender el alfabeto y practicar los sonidos es el primer paso para entender el idioma.'
                ],
                [
                    'titulo' => 'Vocabulario Básico para la Vida Diaria',
                    'media' => '',
                    'concepto' => 'Incluye saludos como "Hola", "Buenos días" y frases útiles como "¿Cómo estás?". También cubre vocabulario relacionado con alimentos, ropa y transporte, útil para viajes y situaciones cotidianas.'
                ],
                [
                    'titulo' => 'Gramática Básica: Verbos y Artículos',
                    'media' => '',
                    'concepto' => 'Los verbos "ser" y "estar" son fundamentales en español, ya que ambos significan "to be" en inglés pero se usan en contextos diferentes. Además, los artículos "el", "la", "los" y "las" concuerdan en género y número con los sustantivos.'
                ],
                [
                    'titulo' => 'Frases Comunes para Conversaciones Cotidianas',
                    'media' => '',
                    'concepto' => 'Aprender frases como "¿Dónde está el baño?" o "¿Cuánto cuesta?" ayuda a desenvolverse en situaciones cotidianas. También se enseñan expresiones de cortesía como "Por favor" y "Gracias".'
                ],
                [
                    'titulo' => 'Escucha Activa y Comprensión Oral',
                    'media' => '',
                    'concepto' => 'Escuchar español de diferentes acentos es clave para mejorar la comprensión. Practicar con audios y videos permite identificar patrones de habla y mejorar la fluidez en las respuestas.'
                ]
            ]),
            'cursoExamen' => json_encode([
                [
                    'pregunta' => '¿Cuántas letras tiene el alfabeto español?',
                    'opciones' => ['26', '27', '25', '30'],
                    'respuestaCorrecta' => '27'
                ],
                [
                    'pregunta' => '¿Qué significa "Hola"?',
                    'opciones' => ['Goodbye', 'Please', 'Hello', 'Thanks'],
                    'respuestaCorrecta' => 'Hello'
                ],
                [
                    'pregunta' => '¿Cuál es el artículo definido para una palabra femenina singular?',
                    'opciones' => ['El', 'La', 'Los', 'Las'],
                    'respuestaCorrecta' => 'La'
                ],
                [
                    'pregunta' => '¿Cuál es la diferencia entre "ser" y "estar"?',
                    'opciones' => [
                        'No hay diferencia', 
                        '"Ser" se usa para estados temporales y "estar" para permanentes', 
                        '"Ser" se usa para características permanentes y "estar" para estados temporales', 
                        'Ambos significan lo mismo'
                    ],
                    'respuestaCorrecta' => '"Ser" se usa para características permanentes y "estar" para estados temporales'
                ],
                [
                    'pregunta' => '¿Qué es la letra "ñ"?',
                    'opciones' => ['Una letra especial del alfabeto español', 'Un acento', 'Un verbo', 'Una preposición'],
                    'respuestaCorrecta' => 'Una letra especial del alfabeto español'
                ]
            ]),
            'createdBy' => 'Junior Zamora',
            'cursoCategoriaId' => 5, // Idiomas
            'created_at' => now(),
            'updated_at' => now(),
        ]);
                                                                
        

    }
}
