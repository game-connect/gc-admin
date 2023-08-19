# Run Development
FROM node:18.12.0-alpine AS development

WORKDIR /node
RUN apk upgrade --update && apk add bash && apk --no-cache add git
COPY package.json ./
COPY . .

EXPOSE 8000