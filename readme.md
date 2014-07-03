
setup:

1. cp includes/config.php.default to includes/config.php open up includes/config.php and set up your s3 bucket and database. you'll need to create the bucket via the web interface or something like that.
2. cp s3/config.inc.php.default s3/config.inc.php and edit it to include your s3 key and secret.
3. fire up mysql. create your database. use it. source migration.sql to create the tables.

load image_test.php for a tiny test.
then try out the site :)

requires php's curl lib

web server must be able to write to public/images

very slow... single process serial download, altering and upload of images :) :) :)
