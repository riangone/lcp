using System;
using System.Collections.Generic;
using System.Threading.Tasks;
using Platform.Domain.Entities;

namespace Platform.Domain.Services
{
    /// <summary>
    /// 実験用：本物のAPIを使わずに「揺らぎ」を再現
    /// </summary>
    public class FakeAI
    {
        private readonly double _temperature;

        public FakeAI(double temperature = 0.7)
        {
            _temperature = temperature;
        }

        /// <summary>
        /// 複数の提案を生成（揺らぎの再現）
        /// </summary>
        public async Task<List<OrderProposal>> GenerateProposals(int currentStock, int safetyStock)
        {
            await Task.Delay(50); // シミュレートされた処理時間

            int baseOrder = Math.Max(0, safetyStock * 2 - currentStock);

            // temperature が高いほど揺らぎが大きい
            int variance = (int)(baseOrder * _temperature * 2);

            var proposals = new List<OrderProposal>();

            // 堅実案（保守的）
            proposals.Add(new OrderProposal
            {
                SuggestedQuantity = Math.Max(0, baseOrder - variance / 2),
                Reasoning = "⚠️ 安全在庫を優先。在庫切れリスクを最小限に抑えます。",
                Confidence = 0.9,
                Temperature = _temperature
            });

            // バランス案（標準）
            proposals.Add(new OrderProposal
            {
                SuggestedQuantity = baseOrder,
                Reasoning = "⚖️ バランス重視。在庫コストと欠品リスクの最適化。",
                Confidence = 0.8,
                Temperature = _temperature
            });

            // 積極案（攻め）
            proposals.Add(new OrderProposal
            {
                SuggestedQuantity = baseOrder + variance,
                Reasoning = "🚀 積極補充。需要増加に備え、在庫を多めに確保。",
                Confidence = 0.6,
                Temperature = _temperature
            });

            return proposals;
        }
    }

    /// <summary>
    /// AIから提案を取得
    /// </summary>
    public static class AiEdgeService
    {
        public static async Task<List<OrderProposal>> GetAiProposals(int currentStock, int safetyStock, double temperature = 0.7)
        {
            var ai = new FakeAI(temperature);
            return await ai.GenerateProposals(currentStock, safetyStock);
        }
    }
}