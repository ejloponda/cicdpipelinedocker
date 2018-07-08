FROM centos/mariadb-101-centos7

RUN chown -R mysql:root /var/lib/mysql 

#ADD init_db.sh /tmp/init_db.sh
#RUN /tmp/init_db.sh

#COPY ./database/rpc.sql /docker-entrypoint-initdb.d/

#CMD  cd /docker-entrypoint-initdb.d && \
 #  mysql -u root rpc < rpc.sql
