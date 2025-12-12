#!/bin/bash

# Script para deploy em Kubernetes
# Uso: ./scripts/deploy-k8s.sh [environment] [namespace]
# Exemplos:
#   ./scripts/deploy-k8s.sh development dev
#   ./scripts/deploy-k8s.sh staging staging
#   ./scripts/deploy-k8s.sh production production

set -e

ENVIRONMENT=${1:-development}
NAMESPACE=${2:-microservices}

# Validar ambiente
if [[ ! "$ENVIRONMENT" =~ ^(development|staging|production)$ ]]; then
    echo "❌ Ambiente inválido: $ENVIRONMENT"
    echo "Use: development, staging ou production"
    exit 1
fi

echo "=========================================="
echo "Deploy em Kubernetes"
echo "Ambiente: $ENVIRONMENT"
echo "Namespace: $NAMESPACE"
echo "=========================================="

# Verificar se kubectl está instalado
if ! command -v kubectl &> /dev/null; then
    echo "❌ kubectl não está instalado. Por favor, instale o kubectl."
    exit 1
fi

# Verificar se kustomize está instalado
if ! command -v kustomize &> /dev/null; then
    echo "❌ kustomize não está instalado. Por favor, instale o kustomize."
    exit 1
fi

echo "✓ kubectl e kustomize encontrados"

# Verificar conexão com cluster
echo ""
echo "Verificando conexão com cluster Kubernetes..."
if ! kubectl cluster-info &> /dev/null; then
    echo "❌ Não foi possível conectar ao cluster Kubernetes"
    echo "Verifique sua configuração de kubeconfig"
    exit 1
fi
echo "✓ Conectado ao cluster Kubernetes"

# Criar namespace se não existir
echo ""
echo "Verificando namespace..."
if ! kubectl get namespace $NAMESPACE &> /dev/null; then
    echo "Criando namespace $NAMESPACE..."
    kubectl create namespace $NAMESPACE
fi
echo "✓ Namespace $NAMESPACE pronto"

# Aplicar configurações
echo ""
echo "Aplicando configurações do Kubernetes..."
kustomize build k8s/overlays/$ENVIRONMENT | kubectl apply -f -

# Aguardar rollout
echo ""
echo "Aguardando rollout dos deployments..."
kubectl rollout status deployment -l app=microservices --namespace=$NAMESPACE --timeout=5m

echo ""
echo "=========================================="
echo "✓ Deploy em Kubernetes concluído!"
echo "=========================================="
echo ""
echo "Verificar status:"
echo "  kubectl get pods -n $NAMESPACE"
echo "  kubectl get services -n $NAMESPACE"
echo ""
echo "Acessar logs:"
echo "  kubectl logs -n $NAMESPACE -l app=microservices -f"
echo ""
