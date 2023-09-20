FROM nginx:1.18.0

COPY nginx.conf /etc/nginx
COPY default-fastcgi /etc/nginx

ENTRYPOINT ["nginx", "-g", "daemon off;"]