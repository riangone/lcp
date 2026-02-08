using Platform.Application.Services;
using Platform.Domain.Core;
using Platform.Infrastructure.Shell;
using System;
using System.Collections.Generic;
using System.Threading.Tasks;

namespace Platform.Api.TestScenarios
{
    /// <summary>
    /// AI三层架构业务场景测试用例
    /// 演示完整的AI建议生成、快照管理和审批流程
    /// </summary>
    public class AiArchitectureTestScenario
    {
        private readonly IAiSuggestionService _aiService;
        private readonly ISnapshotRepository _snapshotRepository;
        private readonly AiIntegrationService _aiIntegrationService;

        public AiArchitectureTestScenario(
            IAiSuggestionService aiService,
            ISnapshotRepository snapshotRepository,
            AiIntegrationService aiIntegrationService)
        {
            _aiService = aiService;
            _snapshotRepository = snapshotRepository;
            _aiIntegrationService = aiIntegrationService;
        }

        /// <summary>
        /// 执行艺术家名称优化建议场景
        /// </summary>
        public async Task ExecuteArtistOptimizationScenario()
        {
            Console.WriteLine("=== 开始执行艺术家名称优化AI建议场景 ===\n");

            // 模拟艺术家数据
            var artistData = new Dictionary<string, object>
            {
                { "ArtistId", 1 },
                { "Name", "The Beatles" }
            };

            Console.WriteLine("1. 非确定性边缘层 - AI建议生成:");
            Console.WriteLine($"   输入数据: {FormatData(artistData)}");

            // 生成AI建议 - 非确定性边缘层
            var suggestions = await _aiService.GenerateSuggestionsAsync(artistData, "Artist");
            Console.WriteLine($"   生成建议数量: {suggestions.Count}");
            foreach (var suggestion in suggestions)
            {
                Console.WriteLine($"   - 建议: {suggestion.Reasoning}");
                Console.WriteLine($"     置信度: {suggestion.Confidence:P}");
                Console.WriteLine($"     模型: {suggestion.ModelInfo.ModelId}");
            }

            Console.WriteLine("\n2. 确定性外壳层 - 快照创建:");
            // 将建议稳定化为快照 - 确定性外壳层
            var snapshotIds = await _aiIntegrationService.GenerateAndStabilizeSuggestionsAsync(artistData, "Artist");
            Console.WriteLine($"   创建快照数量: {snapshotIds.Count}");
            foreach (var id in snapshotIds)
            {
                Console.WriteLine($"   - 快照ID: {id.Substring(0, 8)}...");
            }

            Console.WriteLine("\n3. 确定性外壳层 - 快照检索:");
            // 获取待审批的快照
            var pendingSnapshots = await _snapshotRepository.GetPendingSnapshotsAsync("Artist");
            Console.WriteLine($"   待审批快照数量: {pendingSnapshots.Count}");
            foreach (var snapshot in pendingSnapshots)
            {
                Console.WriteLine($"   - 快照ID: {snapshot.Id.Substring(0, 8)}...");
                Console.WriteLine($"     AI模型: {snapshot.Provenance.AiModelId}");
                Console.WriteLine($"     创建时间: {snapshot.CreatedAt}");
                Console.WriteLine($"     证迹信息: {FormatData(snapshot.Provenance.Metadata)}");
            }

            Console.WriteLine("\n4. 函数式核心层 - 业务规则验证:");
            // 演示业务规则验证 - 函数式核心层
            var businessRules = new[]
            {
                new BusinessRule<Dictionary<string, object>>(
                    entity => entity.ContainsKey("Name") && !string.IsNullOrWhiteSpace(entity["Name"].ToString()),
                    "艺术家名称不能为空"
                ),
                new BusinessRule<Dictionary<string, object>>(
                    entity => entity.ContainsKey("Name") && entity["Name"].ToString().Length <= 120,
                    "艺术家名称不能超过120个字符"
                )
            };

            var validationResult = BusinessRuleValidator.ValidateEntity(artistData, businessRules);
            Console.WriteLine($"   验证结果: {(validationResult.IsValid ? "通过" : "失败")}");
            if (!validationResult.IsValid)
            {
                foreach (var error in validationResult.Errors)
                {
                    Console.WriteLine($"   - 错误: {error}");
                }
            }

            Console.WriteLine("\n=== AI三层架构场景执行完成 ===\n");
        }

        /// <summary>
        /// 执行发票金额预测场景
        /// </summary>
        public async Task ExecuteInvoicePredictionScenario()
        {
            Console.WriteLine("=== 开始执行发票金额预测AI场景 ===\n");

            // 模拟发票数据
            var invoiceData = new Dictionary<string, object>
            {
                { "InvoiceId", 1 },
                { "CustomerId", 10 },
                { "Total", 15.76m }
            };

            Console.WriteLine("1. 非确定性边缘层 - AI预测生成:");
            Console.WriteLine($"   输入数据: {FormatData(invoiceData)}");

            // 生成AI预测
            var prediction = await _aiService.GeneratePredictionAsync(invoiceData, "invoice-total");
            Console.WriteLine($"   预测值: {prediction.PredictedValue}");
            Console.WriteLine($"   置信度: {prediction.Confidence:P}");
            Console.WriteLine($"   模型: {prediction.ModelInfo.ModelId}");
            Console.WriteLine($"   元数据: {FormatData(prediction.Metadata)}");

            Console.WriteLine("\n=== 发票预测场景执行完成 ===\n");
        }

        private string FormatData(object data)
        {
            return System.Text.Json.JsonSerializer.Serialize(data, new System.Text.Json.JsonSerializerOptions 
            { 
                WriteIndented = false 
            });
        }
    }
}