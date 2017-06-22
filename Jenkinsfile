pipeline {
  agent any
  stages {
        try {

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

        } catch (all) {
            slackSend color: '#f44336', channel: '#jenkins', message: "Failed ${env.JOB_NAME} (<${env.BUILD_URL}|build ${env.BUILD_NUMBER}>) - <${env.BUILD_URL}console|click here to see the console output>"
        }
  }
}
