#!/bin/bash

# Script para deploy local com Docker Compose
# Uso: ./scripts/deploy-local.sh

set -e

echo "=========================================="
echo "Iniciando deploy local com Docker Compose"
echo "=========================================="

# Verificar se Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Docker não está instalado. Por favor, instale o Docker."
    exit 1
fi

# Verificar se Docker Compose está instalado
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose não está instalado. Por favor, instale o Docker Compose."
    exit 1
fi

echo "✓ Docker e Docker Compose encontrados"

# Build das imagens
echo ""
echo "Construindo imagens Docker..."
docker-compose build

# Iniciar os serviços
echo ""
echo "Iniciando serviços..."
docker-compose up -d

# Aguardar os serviços ficarem prontos
echo ""
echo "Aguardando serviços ficarem prontos..."
sleep 10

# Verificar status
echo ""
echo "Status dos serviços:"
docker-compose ps

echo ""
echo "=========================================="
echo "✓ Deploy local concluído com sucesso!"
echo "=========================================="
echo ""
echo "Serviços disponíveis:"
echo "  - Kong API Gateway: http://localhost:8000"
echo "  - Kong Admin: http://localhost:8001"
echo "  - RabbitMQ Management: http://localhost:15672"
echo "  - Prometheus: http://localhost:9090"
echo "  - Grafana: http://localhost:3000"
echo ""
echo "Para parar os serviços, execute:"
echo "  docker-compose down"
echo ""
