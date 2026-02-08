using System;

namespace Platform.Domain.Core
{
    /// <summary>
    /// 实体状态转换器
    /// 这是一个纯函数实现，不产生副作用
    /// </summary>
    public static class EntityStateTransition
    {
        /// <summary>
        /// 应用状态转换函数到实体
        /// </summary>
        /// <typeparam name="T">实体类型</typeparam>
        /// <param name="entity">原始实体</param>
        /// <param name="transitionFunction">状态转换函数</param>
        /// <returns>转换后的新实体</returns>
        public static T ApplyTransition<T>(T entity, Func<T, T> transitionFunction)
        {
            return transitionFunction(entity);
        }
        
        /// <summary>
        /// 验证状态转换是否有效
        /// </summary>
        /// <typeparam name="T">实体类型</typeparam>
        /// <param name="fromState">源状态</param>
        /// <param name="toState">目标状态</param>
        /// <param name="validationRules">验证规则</param>
        /// <returns>验证结果</returns>
        public static ValidationResult ValidateTransition<T>(T fromState, T toState, params Func<T, T, ValidationResult>[] validationRules)
        {
            foreach (var rule in validationRules)
            {
                var result = rule(fromState, toState);
                if (!result.IsValid)
                {
                    return result;
                }
            }
            
            return ValidationResult.Success;
        }
    }
}