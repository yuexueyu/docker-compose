FROM daocloud.io/library/nginx:1.13.0-alpine

RUN cd / \
    && mkdir -m 775 logs \
    && chown nginx:nginx logs \
    && mkdir -m 775 www \
    && chown nginx:nginx www \