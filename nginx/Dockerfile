FROM nginx:1.17.2

RUN cd / \
    && mkdir -m 775 logs \
    && chown nginx:nginx logs \
    && mkdir -m 775 www \
    && chown nginx:nginx www \
    && mkdir -m 775 wwwroot \
    && chown nginx:nginx wwwroot \