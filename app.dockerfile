FROM bylexus/apache-php55

RUN a2enmod rewrite \
&& sudo apt-get update \
&& sudo apt-get install php5-curl php5-json php5-mcrypt -y \
&& sudo php5enmod mcrypt
#&& sudo service apache2 reload

