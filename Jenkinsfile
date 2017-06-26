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
            sh 'vendor/bin/phpunit tests/suites --bootstrap tests/bootstrap.php  --coverage-html tests/reports/coverage.html --whitelist src/WonderWp/Framework --log-junit tests/reports/phunit.xml';
            }
        }
        stage('notify'){
            steps {
                script {
                    slackSend(message: "Completed ${env.JOB_NAME} (<${env.BUILD_URL}|build ${env.BUILD_NUMBER}>) successfully", channel: '#jenkins', color: 'good', failOnError: true, teamDomain: 'wdf-team', token: 'ebmZ6kgsvWsgFVUY3UViZPOS')
                }
            }
        }
  }
}
