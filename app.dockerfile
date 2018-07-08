FROM bylexus/apache-php55

RUN a2enmod rewrite \
&&  apt-get update \
&&  apt-get install php5-curl php5-json php5-mcrypt bash -y \
&&  php5enmod mcrypt 
