using System;
using System.Collections.Generic;
using Platform.Domain.Entities;

namespace Platform.Domain.Services
{
    /// <summary>
    /// 純粋関数型コア（副作用ゼロ）
    /// 全ての入力は引数、出力は戻り値のみ
    /// </summary>
    public class InventoryCore
    {
        /// <summary>
        /// 発注後の在庫を計算（不変条件を守る）
        /// </summary>
        public static int? CalculateNewStock(int currentStock, int orderQuantity, int maxCapacity)
        {
            int newStock = currentStock + orderQuantity;

            // 不変条件：在庫は0以上、最大容量以下
            if (newStock < 0 || newStock > maxCapacity)
            {
                return null;
            }

            return newStock;
        }

        /// <summary>
        /// 提案を検証（副作用ゼロ）
        /// </summary>
        public static (bool isValid, string message) ValidateProposal(OrderProposal proposal, InventoryState currentState)
        {
            // 提案自体の妥当性
            if (!proposal.IsValid(currentState.MaxCapacity))
            {
                return (false, "発注数が最大容量を超えています");
            }

            // 発注後の在庫を計算
            var newStock = CalculateNewStock(
                currentState.CurrentStock,
                proposal.SuggestedQuantity,
                currentState.MaxCapacity
            );

            if (newStock == null)
            {
                return (false, "発注後の在庫が不正な値になります");
            }

            // 安全在庫を下回らないか
            if (newStock < currentState.SafetyStock)
            {
                return (false, "安全在庫を確保できません");
            }

            return (true, "承認可能");
        }

        /// <summary>
        /// 自動承認の判断（信頼度とリスクで判断）
        /// </summary>
        public static bool AutoApprove(OrderProposal proposal, InventoryState currentState)
        {
            var (isValid, _) = ValidateProposal(proposal, currentState);

            // 信頼度80%以上 かつ バリデーションOK
            return isValid && proposal.Confidence >= 0.8;
        }
    }
}