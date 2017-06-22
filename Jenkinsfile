node {

    slackSend color: '#4CAF50', channel: '#devops', message: "Started ${env.JOB_NAME} (<${env.BUILD_URL}|build ${env.BUILD_NUMBER}>)"

    try {

        stage("composer_install") {
            // Run `composer update` as a shell script
            sh 'composer install'
        }
        stage("phpunit") {
            // Run PHPUnit
            sh 'vendor/bin/phpunit tests/suites --coverage-html tests/reports/coverage.html --whitelist src/WonderWp/Framework --log-junit tests/reports/phunit.xml';
        }

        slackSend color: '#4CAF50', channel: '#devops', message: "Completed ${env.JOB_NAME} (<${env.BUILD_URL}|build ${env.BUILD_NUMBER}>) successfully"

    } catch (all) {
        slackSend color: '#f44336', channel: '#devops', message: "Failed ${env.JOB_NAME} (<${env.BUILD_URL}|build ${env.BUILD_NUMBER}>) - <${env.BUILD_URL}console|click here to see the console output>"
    }
}
