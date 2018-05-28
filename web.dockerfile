#FROM httpd:2.4

#ADD ./vhost.conf /etc/apache2/apache2.conf

FROM nginx:1.10

ADD vhost.conf /etc/nginx/conf.d/default.conf