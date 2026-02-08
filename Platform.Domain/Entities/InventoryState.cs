using System;
using System.Collections.Generic;
using System.Text.Json.Serialization;

namespace Platform.Domain.Entities
{
    /// <summary>
    /// 在庫状態（純粋データ）
    /// </summary>
    public class InventoryState
    {
        public int CurrentStock { get; set; }
        public int SafetyStock { get; set; }
        public int MaxCapacity { get; set; }

        /// <summary>
        /// 不変条件チェック（副作用ゼロ）
        /// </summary>
        public bool ValidateInvariants()
        {
            return CurrentStock >= 0 &&
                   CurrentStock <= MaxCapacity &&
                   SafetyStock >= 0;
        }
    }

    /// <summary>
    /// 発注提案（AIが生成）
    /// </summary>
    public class OrderProposal
    {
        public int SuggestedQuantity { get; set; }
        public string Reasoning { get; set; } = string.Empty;
        public double Confidence { get; set; }
        public double Temperature { get; set; } = 0.7;

        /// <summary>
        /// 提案の妥当性チェック（副作用ゼロ）
        /// </summary>
        public bool IsValid(int maxOrder)
        {
            return SuggestedQuantity >= 0 && SuggestedQuantity <= maxOrder;
        }
    }

    /// <summary>
    /// AI提案のスナップショット（Stabilize済み）
    /// </summary>
    public class ProposalSnapshot
    {
        public string Id { get; set; } = string.Empty;
        public OrderProposal Proposal { get; set; } = new OrderProposal();
        public string AiModel { get; set; } = string.Empty;
        public DateTime CreatedAt { get; set; } = DateTime.UtcNow;
        public string DecisionTrace { get; set; } = string.Empty;
    }

    /// <summary>
    /// 実行された発注
    /// </summary>
    public class OrderExecution
    {
        public string SnapshotId { get; set; } = string.Empty;
        public int ApprovedQuantity { get; set; }
        public string ApprovedBy { get; set; } = string.Empty;
        public DateTime ExecutedAt { get; set; } = DateTime.UtcNow;
        public int NewStockLevel { get; set; }
    }
}