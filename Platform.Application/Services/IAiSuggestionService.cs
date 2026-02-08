using System.Collections.Generic;
using System.Threading.Tasks;

namespace Platform.Application.Services
{
    /// <summary>
    /// AI建议服务接口
    /// 用于生成基于AI的建议和预测
    /// </summary>
    public interface IAiSuggestionService
    {
        /// <summary>
        /// 为指定模型生成AI建议
        /// </summary>
        /// <typeparam name="T">数据类型</typeparam>
        /// <param name="currentData">当前数据</param>
        /// <param name="modelType">模型类型</param>
        /// <returns>AI生成的建议列表</returns>
        Task<List<AiSuggestion<T>>> GenerateSuggestionsAsync<T>(T currentData, string modelType);
        
        /// <summary>
        /// 为指定模型生成AI预测
        /// </summary>
        /// <typeparam name="T">数据类型</typeparam>
        /// <param name="inputData">输入数据</param>
        /// <param name="predictionType">预测类型</param>
        /// <returns>AI生成的预测结果</returns>
        Task<AiPrediction<T>> GeneratePredictionAsync<T>(T inputData, string predictionType);
    }
    
    /// <summary>
    /// AI建议类
    /// </summary>
    /// <typeparam name="T">建议的数据类型</typeparam>
    public class AiSuggestion<T>
    {
        public T SuggestedValue { get; set; }
        public double Confidence { get; set; } // 置信度，0-1之间
        public string Reasoning { get; set; } = string.Empty;
        public AiModelInfo ModelInfo { get; set; } = new AiModelInfo();
    }
    
    /// <summary>
    /// AI预测类
    /// </summary>
    /// <typeparam name="T">预测的数据类型</typeparam>
    public class AiPrediction<T>
    {
        public T PredictedValue { get; set; }
        public double Confidence { get; set; } // 置信度，0-1之间
        public Dictionary<string, object> Metadata { get; set; } = new Dictionary<string, object>();
        public AiModelInfo ModelInfo { get; set; } = new AiModelInfo();
    }
    
    /// <summary>
    /// AI模型信息类
    /// </summary>
    public class AiModelInfo
    {
        public string ModelId { get; set; } = string.Empty;
        public string ModelVersion { get; set; } = string.Empty;
        public DateTime ModelLastUpdated { get; set; } = DateTime.UtcNow;
    }
}