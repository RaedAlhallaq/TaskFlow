# Multi-stage Dockerfile for the Go Books REST API (TaskFlow)
# Build context: go-backend/

# ---- Build stage ----
FROM golang:1.22-alpine AS builder

WORKDIR /src
COPY go.mod ./
COPY *.go ./

# Build a static binary
RUN CGO_ENABLED=0 GOOS=linux go build -trimpath -ldflags="-s -w" -o /out/booksapi .

# ---- Runtime stage ----
FROM alpine:3.20

RUN apk add --no-cache ca-certificates wget \
    && addgroup -S app && adduser -S app -G app

WORKDIR /app
COPY --from=builder /out/booksapi /app/booksapi

USER app
EXPOSE 8080

HEALTHCHECK --interval=30s --timeout=5s --start-period=5s --retries=3 \
    CMD wget -qO- http://127.0.0.1:8080/health >/dev/null 2>&1 || exit 1

CMD ["/app/booksapi"]
