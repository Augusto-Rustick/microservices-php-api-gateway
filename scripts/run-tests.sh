#!/bin/bash

# Script para executar testes de todos os microserviços
# Uso: ./scripts/run-tests.sh [service]
# Exemplos:
#   ./scripts/run-tests.sh          # Executa testes de todos os serviços
#   ./scripts/run-tests.sh catalog  # Executa testes apenas do catalog

set -e

SERVICES=${1:-"catalog orders promotions payment"}

echo "=========================================="
echo "Executando testes dos microserviços"
echo "=========================================="

TOTAL_COVERAGE=0
SERVICE_COUNT=0

for service in $SERVICES; do
    if [ ! -d "$service" ]; then
        echo "⚠️  Serviço $service não encontrado"
        continue
    fi

    echo ""
    echo "Testando $service..."
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

    # Executar testes
    if [ -d "tests" ] && [ "$(ls -A tests)" ]; then
        echo "Executando PHPUnit..."
        vendor/bin/phpunit --coverage-clover=coverage.xml --coverage-html=coverage-report

        # Extrair cobertura
        if [ -f "coverage.xml" ]; then
            COVERAGE=$(grep -oP 'line-rate="\K[^"]*' coverage.xml | head -1)
            COVERAGE_PERCENT=$(echo "scale=2; $COVERAGE * 100" | bc)
            echo "✓ Cobertura: ${COVERAGE_PERCENT}%"
            
            # Verificar se atende ao mínimo de 80%
            if (( $(echo "$COVERAGE_PERCENT >= 80" | bc -l) )); then
                echo "✓ Cobertura acima de 80%"
            else
                echo "⚠️  Cobertura abaixo de 80%: ${COVERAGE_PERCENT}%"
            fi
        fi
    else
        echo "⚠️  Nenhum teste encontrado em $service/tests"
    fi

    cd ..
    SERVICE_COUNT=$((SERVICE_COUNT + 1))
done

echo ""
echo "=========================================="
echo "✓ Testes concluídos!"
echo "=========================================="
echo ""
echo "Relatórios de cobertura disponíveis em:"
for service in $SERVICES; do
    if [ -d "$service/coverage-report" ]; then
        echo "  - $service/coverage-report/index.html"
    fi
done
echo ""
