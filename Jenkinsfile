pipeline {
  agent any
  stages {
        stage("Build") {
            steps{
                echo "Starting Build, triggered by $BRANCH_NAME";
                echo "Building ${env.BUILD_ID} on ${env.JOB_URL}";
                sh 'composer install'
            }
        }
        stage("Test") {
            steps {
            echo 'Starting PHPUnit Tests'
            // Run PHPUnit
            sh 'vendor/bin/phpunit tests/suites --coverage-html tests/reports/coverage.html --whitelist src/WonderWp/Framework --log-junit tests/reports/phunit.xml';
            }
        }
        stage('notify'){
            steps {
                script {
                    slackSend color: '#4CAF50', channel: '#jenkins', message: "Completed ${env.JOB_NAME} (<${env.BUILD_URL}|build ${env.BUILD_NUMBER}>) successfully"
                }
            }
        }
  }
}
