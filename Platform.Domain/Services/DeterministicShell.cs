using System;
using System.Collections.Generic;
using System.Security.Cryptography;
using System.Text;
using System.Text.Json;
using Platform.Domain.Entities;

namespace Platform.Domain.Services
{
    /// <summary>
    /// 确定性外壳层，处理副作用和快照
    /// </summary>
    public static class DeterministicShell
    {
        /// <summary>
        /// スナップショット化（JSON正規化 + ハッシュ）
        /// </summary>
        public static ProposalSnapshot Stabilize(OrderProposal proposal, string aiModel, string trace)
        {
            // JSON正規化
            var normalized = JsonSerializer.Serialize(proposal, new JsonSerializerOptions
            {
                PropertyNamingPolicy = JsonNamingPolicy.CamelCase,
                WriteIndented = false
            });

            // ハッシュID生成
            using var sha256 = SHA256.Create();
            var hashBytes = sha256.ComputeHash(Encoding.UTF8.GetBytes(normalized));
            var hashId = Convert.ToHexString(hashBytes).ToLower();

            return new ProposalSnapshot
            {
                Id = hashId,
                Proposal = proposal,
                AiModel = aiModel,
                CreatedAt = DateTime.UtcNow,
                DecisionTrace = trace
            };
        }

        /// <summary>
        /// 在庫状態を更新
        /// </summary>
        public static InventoryState UpdateInventory(InventoryState currentState, int newStock)
        {
            return new InventoryState
            {
                CurrentStock = newStock,
                SafetyStock = currentState.SafetyStock,
                MaxCapacity = currentState.MaxCapacity
            };
        }
    }
}