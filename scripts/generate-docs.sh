#!/bin/bash

# Script para gerar documentação Swagger de todos os microserviços
# Uso: ./scripts/generate-docs.sh [service]
# Exemplos:
#   ./scripts/generate-docs.sh          # Gera docs de todos os serviços
#   ./scripts/generate-docs.sh catalog  # Gera docs apenas do catalog

set -e

SERVICES=${1:-"catalog orders promotions payment"}

echo "=========================================="
echo "Gerando documentação Swagger"
echo "=========================================="

for service in $SERVICES; do
    if [ ! -d "$service" ]; then
        echo "⚠️  Serviço $service não encontrado"
        continue
    fi

    echo ""
    echo "Gerando documentação para $service..."
    echo "---"

    cd $service

    # Verificar se composer.json existe
    if [ ! -f "composer.json" ]; then
        echo "⚠️  composer.json não encontrado em $service"
        cd ..
        continue
    fi

    # Instalar dependências se necessário
    if [ ! -d "vendor" ]; then
        echo "Instalando dependências..."
        composer install --no-interaction --prefer-dist
    fi

    # Gerar documentação
    if command -v vendor/bin/openapi &> /dev/null; then
        echo "Executando OpenAPI generator..."
        vendor/bin/openapi src -o swagger.json
        echo "✓ Documentação gerada: swagger.json"
    else
        echo "⚠️  OpenAPI generator não encontrado"
    fi

    cd ..
done

echo ""
echo "=========================================="
echo "✓ Documentação gerada com sucesso!"
echo "=========================================="
echo ""
echo "Arquivos de documentação disponíveis em:"
for service in $SERVICES; do
    if [ -f "$service/swagger.json" ]; then
        echo "  - $service/swagger.json"
    fi
done
echo ""
echo "Para visualizar a documentação, acesse:"
echo "  - http://localhost:8000/catalog/docs"
echo "  - http://localhost:8000/orders/docs"
echo "  - http://localhost:8000/promotions/docs"
echo "  - http://localhost:8000/payment/docs"
echo ""
