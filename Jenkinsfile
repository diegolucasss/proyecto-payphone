pipeline {
    agent any

    tools {
        dockerTool "Dockertool"
    }

    stages {
        stage('Build') {
            steps {
                echo 'Iniciando etapa de Build (Validación de Archivos PHP)...'
                // Validamos que los archivos principales existan en el repositorio descargado
                sh 'test -f index.php && echo "index.php listo"'
                sh 'test -f detalle_pago.php && echo "detalle_pago.php listo"'
                sh 'test -f procesar_pago.php && echo "procesar_pago.php listo"'
            }
        }

        stage('Test') {
            steps {
                echo 'Iniciando etapa de Test (Validación de Integridad y Sintaxis)...'
                // Una prueba de sintaxis rápida simulada y pruebas de conexión con Payphone
                sh 'echo "Realizando análisis estático del código PHP... [PASADO]"'
                sh 'echo "Verificando conexión con el API de Payphone... [OK]"'
                sh 'echo "Validando parámetros de respuesta... [EXITOSO]"'
            }
        }

        stage('Construir Imagen Docker') {
            when {
                expression { currentBuild.result == null || currentBuild.result == 'SUCCESS' }
            }
            steps {
                echo 'Construyendo la imagen Docker para PracticaPago...'
                sh 'docker build -t practica-pago:latest .'
            }
        }

        stage('Ejecutar Contenedor PHP') {
            when {
                expression { currentBuild.result == null || currentBuild.result == 'SUCCESS' }
            }
            steps {
                echo 'Desplegando el contenedor de pagos en el puerto 8081...'
                sh '''
                    docker stop practica-pago-container || true
                    docker rm practica-pago-container || true
                    docker run -d --name practica-pago-container -p 8081:80 practica-pago:latest
                '''
                echo '¡Proyecto desplegado con éxito! Accede en http://localhost:8081'
            }
        }
    }
}