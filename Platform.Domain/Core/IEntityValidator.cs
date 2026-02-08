using System.Collections.Generic;

namespace Platform.Domain.Core
{
    /// <summary>
    /// 实体验证器接口，用于验证业务实体
    /// 这是一个纯函数接口，不产生副作用
    /// </summary>
    /// <typeparam name="T">要验证的实体类型</typeparam>
    public interface IEntityValidator<in T>
    {
        /// <summary>
        /// 验证实体是否符合业务规则
        /// </summary>
        /// <param name="entity">要验证的实体</param>
        /// <returns>验证结果，包含所有验证错误</returns>
        ValidationResult Validate(T entity);
    }

    /// <summary>
    /// 验证结果类
    /// </summary>
    public class ValidationResult
    {
        public bool IsValid { get; set; }
        public List<string> Errors { get; set; } = new List<string>();
        
        public static ValidationResult Success => new ValidationResult { IsValid = true };
        
        public static ValidationResult Failure(List<string> errors) => 
            new ValidationResult { IsValid = false, Errors = errors };
    }
}