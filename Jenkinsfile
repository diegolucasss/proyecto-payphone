stage('Ejecutar Contenedor PHP') {
            when {
                expression { currentBuild.result == null || currentBuild.result == 'SUCCESS' }
            }
            steps {
                echo 'Desplegando el contenedor de pagos en el puerto 8000...'
                sh '''
                    docker stop practica-pago-container || true
                    docker rm practica-pago-container || true
                    
                    # Cambiamos el puerto izquierdo a 8000
                    docker run -d --name practica-pago-container -p 8000:80 practica-pago:latest
                '''
                echo '¡Proyecto desplegado con éxito! Accede en http://localhost:8000'
            }
        }