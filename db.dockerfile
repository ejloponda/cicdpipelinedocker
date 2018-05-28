FROM centos/mariadb-101-centos7

RUN chown -R mysql:root /var/lib/mysql 

ADD /database/rpc.sql /docker-entrypoint-initdb.d/

