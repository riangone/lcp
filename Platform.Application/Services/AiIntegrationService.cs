using Platform.Infrastructure.Shell;
using System;
using System.Collections.Generic;
using System.Security.Cryptography;
using System.Text;
using System.Threading.Tasks;

namespace Platform.Application.Services
{
    /// <summary>
    /// AI集成服务
    /// 协调AI建议生成和快照存储
    /// </summary>
    public class AiIntegrationService
    {
        private readonly IAiSuggestionService _aiService;
        private readonly ISnapshotRepository _snapshotRepository;

        public AiIntegrationService(IAiSuggestionService aiService, ISnapshotRepository snapshotRepository)
        {
            _aiService = aiService;
            _snapshotRepository = snapshotRepository;
        }

        /// <summary>
        /// 生成AI建议并创建快照
        /// </summary>
        /// <typeparam name="T">数据类型</typeparam>
        /// <param name="currentData">当前数据</param>
        /// <param name="modelType">模型类型</param>
        /// <returns>创建的快照ID列表</returns>
        public async Task<List<string>> GenerateAndStabilizeSuggestionsAsync<T>(T currentData, string modelType)
        {
            // 生成AI建议
            var suggestions = await _aiService.GenerateSuggestionsAsync(currentData, modelType);
            
            var snapshotIds = new List<string>();
            
            foreach (var suggestion in suggestions)
            {
                // 为每个建议创建快照
                var snapshot = new Snapshot
                {
                    ModelType = modelType,
                    Data = suggestion.SuggestedValue,
                    Provenance = new Provenance
                    {
                        AiModelId = suggestion.ModelInfo.ModelId,
                        InputHash = ComputeInputHash(currentData),
                        Timestamp = DateTime.UtcNow,
                        Metadata = new Dictionary<string, object>
                        {
                            { "confidence", suggestion.Confidence },
                            { "reasoning", suggestion.Reasoning },
                            { "model_version", suggestion.ModelInfo.ModelVersion }
                        }
                    }
                };
                
                // 保存快照
                var snapshotId = await _snapshotRepository.SaveSnapshotAsync(snapshot);
                snapshotIds.Add(snapshotId);
            }
            
            return snapshotIds;
        }
        
        /// <summary>
        /// 计算输入数据的哈希值，用于证迹追踪
        /// </summary>
        /// <param name="data">输入数据</param>
        /// <returns>SHA256哈希值</returns>
        private string ComputeInputHash<T>(T data)
        {
            var jsonString = System.Text.Json.JsonSerializer.Serialize(data);
            using var sha256 = SHA256.Create();
            var hashBytes = sha256.ComputeHash(Encoding.UTF8.GetBytes(jsonString));
            return Convert.ToBase64String(hashBytes);
        }
    }
}