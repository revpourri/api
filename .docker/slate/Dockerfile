FROM ruby:2.5-alpine

RUN apk --no-cache add \
        g++ \
        gcc \
        libc-dev \
        make \
        nodejs \
        git \
    && gem install bundler

RUN mkdir -p /app/slate
# Use 2.4.  2.5 has formatting issues
RUN cd /app/slate/; git clone --branch v2.4.0 --depth 1 https://github.com/slatedocs/slate .
WORKDIR /app/slate
EXPOSE 4567

RUN bundle

CMD ["bundle", "exec", "middleman", "server"]