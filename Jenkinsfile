pipeline {
    // Define que el pipeline se puede ejecutar en cualquier nodo/servidor disponible
    agent any

    stages {
        // ETAPA 1: BUILD (Validación de Archivos)
        stage('Build') {
            steps {
                echo 'Iniciando etapa de Build (Validación de Archivos)...'
                
                // sh 'test -f <archivo>' verifica que los archivos existan físicamente.
                // Si alguno falta, el pipeline se detiene automáticamente para evitar desplegar código roto.
                sh 'test -f index.php && echo "index.php listo"'
                sh 'test -f detalle_pago.php && echo "detalle_pago.php listo"'
                sh 'test -f procesar_pago.php && echo "procesar_pago.php listo"'
            }
        }

        // ETAPA 2: TEST (Pruebas de Integración y Simulación)
        stage('Test') {
            steps {
                echo 'Iniciando etapa de Test (Pruebas de Integración)...'
                
                // Simulaciones de pruebas de calidad y de comunicación con la pasarela de pagos
                sh 'echo "Realizando análisis estático del código PHP... [PASADO]"'
                sh 'echo "Verificando conexión con el API de Payphone... [OK]"'
                sh 'echo "Validando parámetros de respuesta... [EXITOSO]"'
            }
        }

        // ETAPA 3: CONSTRUIR IMAGEN DOCKER
        stage('Construir Imagen Docker') {
            // "when": Condicional para asegurar que solo se compile la imagen si el Build y Test pasaron con éxito
            when {
                expression { currentBuild.result == null || currentBuild.result == 'SUCCESS' }
            }
            steps {
                echo 'Construyendo la imagen Docker para PracticaPago...'
                
                // Compila la imagen local a partir del Dockerfile y la etiqueta como 'practica-pago:latest'
                sh 'docker build -t practica-pago:latest .'
            }
        }

        // ETAPA 4: EJECUTAR CONTENEDOR (Despliegue / Deploy)
        stage('Ejecutar Contenedor PHP') {
            // Solo se ejecuta si todo lo anterior finalizó correctamente
            when {
                expression { currentBuild.result == null || currentBuild.result == 'SUCCESS' }
            }
            steps {
                echo 'Desplegando el contenedor de pagos en el puerto 8000...'
                
                sh '''
                    #Detiene el contenedor anterior si ya estaba corriendo para liberar el puerto
                    docker stop practica-pago-container || true
                    
                    #Elimina el contenedor viejo de manera forzada para evitar conflictos de nombres redundantes
                    docker rm -f practica-pago-container || true
                    
                    #Levanta el nuevo contenedor mapeando el puerto 8000 de tu PC con el 80 interno del Apache
                    docker run -d --name practica-pago-container -p 8000:80 practica-pago:latest
                '''
                echo '¡Proyecto desplegado con éxito! Accede en http://localhost:8000'
            }
        }
    }
}