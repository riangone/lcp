using System.Collections.Generic;
using System.Threading.Tasks;

namespace Platform.Infrastructure.Shell
{
    /// <summary>
    /// 快照仓库接口
    /// </summary>
    public interface ISnapshotRepository
    {
        /// <summary>
        /// 保存快照
        /// </summary>
        Task<string> SaveSnapshotAsync(Snapshot snapshot);
        
        /// <summary>
        /// 获取快照
        /// </summary>
        Task<Snapshot?> GetSnapshotAsync(string id);
        
        /// <summary>
        /// 获取待审批的快照列表
        /// </summary>
        Task<List<Snapshot>> GetPendingSnapshotsAsync(string modelType);
        
        /// <summary>
        /// 更新快照状态
        /// </summary>
        Task UpdateSnapshotStatusAsync(string id, SnapshotStatus status, string? approvedBy = null);
    }
}