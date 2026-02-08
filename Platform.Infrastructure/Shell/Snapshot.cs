using System;
using System.Collections.Generic;

namespace Platform.Infrastructure.Shell
{
    /// <summary>
    /// 快照类，用于存储从非确定性边缘传来的数据及其证迹
    /// </summary>
    public class Snapshot
    {
        public string Id { get; set; } = Guid.NewGuid().ToString();
        public string ModelType { get; set; }
        public object Data { get; set; }
        public Provenance Provenance { get; set; }
        public DateTime CreatedAt { get; set; } = DateTime.UtcNow;
        public DateTime? ApprovedAt { get; set; }
        public string? ApprovedBy { get; set; }
        public SnapshotStatus Status { get; set; } = SnapshotStatus.Pending;
    }
    
    /// <summary>
    /// 证迹类，记录AI决策的相关信息
    /// </summary>
    public class Provenance
    {
        public string AiModelId { get; set; }
        public string InputHash { get; set; }
        public DateTime Timestamp { get; set; } = DateTime.UtcNow;
        public Dictionary<string, object> Metadata { get; set; } = new Dictionary<string, object>();
    }
    
    /// <summary>
    /// 快照状态枚举
    /// </summary>
    public enum SnapshotStatus
    {
        Pending,    // 待审批
        Approved,   // 已批准
        Rejected,   // 已拒绝
        Applied     // 已应用到主数据
    }
}